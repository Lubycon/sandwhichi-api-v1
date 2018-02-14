<?php

namespace App\Classes;

use Storage;
use Abort;
use Log;

use Illuminate\Support\Str;
use Intervention;

use App\Models\Image;
use App\Models\ImageGroup;

class FileUpload
{
    // config info
    private $storage;
    private $storagePath;
    private $bucket;
    private $tempStorage;
    private $ownCheckers;
    private $needUpload;
    // config info

    // model
    private $model;
    private $modelName;
    private $modelId;
    private $groupModel;
    private $createModel;
    // model

    // data
    private $inputFile;

    // info
    private $isGroup;
    private $responsiveResolution;
    // info


    public function __construct(){
        // init config...
        $this->initConfig();
    }

    // init function
    private function initConfig(){
        $this->storage = Storage::disk(config('filesystems.default'));
        $this->storagePath = env('S3_PATH');
        $this->responsiveResolution = config('filesystems.responsive_resolution');
        $this->tempStorage = config('filesystems.temp_storage');
        $this->ownCheckers = [
            "snake" => config('filesystems.own_checker'),
            "camel" => camel_case(config('filesystems.own_checker')),
        ];
        $this->bucket = env('S3_BUCKET');
    }
    // init function

    // progress functions
    public function upload($model, $inputFile, $isGroup=false){
        $this->needUpload = $this->checkNeedUpload($inputFile);
//        try{
            if( !$this->needUpload ){
                $this->setBasicVariable($model, $inputFile);
                $this->modelName = $this->getModelName($this->model);
                $this->modelId = $this->getModelId($this->model);
                $this->isGroup = $isGroup;
                $this->groupModel = $this->createImageGroupModel($this->inputFile);
                $this->inputFile = $this->setToArray($this->inputFile);
                $this->inputFile = $this->fileTypeCheck($this->inputFile);
                if( gettype($this->inputFile[0]) === 'object' ){
                    $this->inputFile = $this->uploadS3File($this->inputFile);
                }else{
                    $this->inputFile = $this->uploadS3Binery($this->inputFile);
                }
                $this->createModel = $this->createImageModel($this->inputFile);
            }
            return $this;
//        }catch(\Exception $e){
//            Abort::Error('0050',$e->getMessage());
//        }
    }

    // progress functions

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////////    UPLOAD TO STORAGE METHOD AREA START
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    protected function responsiveUploadUrl($file){
        $image = $this->getResizeImages($file);
        $uploadPath = $this->modelName.'/'.$this->modelId.'/'.$this->setRandomFileName();
        foreach($image as $key => $value){
            $this->storage->getDriver()->getAdapter()->getClient()->upload(
                $this->bucket, // upload bucket
                $uploadPath.$key, $value['image'], // upload path.file name
                'public-read', // permission
                ['params' => [ // metadata
                    'ContentType' => $value['mime'],
                ]]);
        }
        return $this->storagePath.$uploadPath;
    }
    protected function responsiveDeleteUrl($url){
        foreach($this->responsiveResolution as $key => $value){
            $path = $this->getInternalS3Url($url.$value);
            if($this->storage->exists($path)) {
                $this->storage->delete($path);
            }else{
                // TODO :: not exist file delete request
            }
        }
        return true;
    }


    private function uploadS3File($inputFile){
        foreach($inputFile as $key => $value){
            $value->url = $this->responsiveUploadUrl($value);
        }
        return $inputFile;
    }


    private function uploadS3Binery($inputFile){
        foreach($inputFile as $key => $value){
            if( is_null($value['type']) ) unset($inputFile[$key]); // null image
            if( isset( $value['id'] ) ){ //update or delete
                if( $value['deleted'] ){ // delete
                    if( $value[$this->ownCheckers['camel']] && $value['type'] == 'url' ) $this->responsiveDeleteUrl($value['file']);
                    Image::find($value['id'])->delete();
                    // TODO :: S3 original file remove
                    unset($inputFile[$key]);
                }else{ // update
                    if( $value[$this->ownCheckers['camel']] ){ // if in our storage
                        // TODO :: S3 original file remove
                        if( $value['type'] == 'base64' ){ // If get new image
                            $newUrl = $this->responsiveUploadUrl($value['file']);
                        }else if( $value['type'] == 'url'  ){ // Or user not change image
                            // pass
                        }else{
                            Abort::Error('0070','Unknown Update Logic...');
                        }
                    }
                    $inputFile[$key]['url'] = isset($newUrl) ? $newUrl : $value['file'];
                }
            }else{ //create
                if( $value['type'] == 'base64' ) $newUrl = $this->responsiveUploadUrl($value['file']);
                $inputFile[$key]['url'] = isset($newUrl) ? $newUrl : $value['file'];
            }
        }
        return $inputFile;
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////////    UPLOAD TO STORAGE METHOD AREA END
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////////    CREATE MODEL METHOD AREA START
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    protected function createImageModel($inputFile){
        $images = [];
        foreach($inputFile as $key => $value ){
            if( gettype($value) === 'object' ){
                if( gettype($value) === 'object' ){
                    $images[] = Image::create([
                        "index" => 0,
                        "url" => $value->url,
                        $this->ownCheckers['snake'] => true,
                        "image_group_id" => $this->isGroup ? $this->groupModel['id'] : null,
                    ]);
                }
            }else{
                $ownerCheck = $this->ownerCheck($value);
                if( isset($value['id']) ){ // update
                    $images[] = $image = Image::findOrFail($value['id']);
                    $image->update([
                        "index" => isset($value['index']) ? $value['index'] : 0,
                        "url" => $value['url'],
                        $this->ownCheckers['snake'] => $ownerCheck,
                        "image_group_id" => $this->isGroup ? $this->groupModel['id'] : null,
                    ]);
                }else{ // create
                    $images[] = Image::create([
                        "index" => isset($value['index']) ? $value['index'] : 0,
                        "url" => $value['url'],
                        $this->ownCheckers['snake'] => $ownerCheck,
                        "image_group_id" => $this->isGroup ? $this->groupModel['id'] : null,
                    ]);
                }
            }
        }
        return $this->isGroup ? $this->groupModel : $images ;
    }
    protected function createImageGroupModel($inputFile){
        if( $this->isGroup ){
            if( $groupId = $this->findGroupExist($inputFile) ){
                $model = ImageGroup::findOrFail($groupId);
            }else{
                $model = ImageGroup::create([]);
            }
        }
        return isset($model) ? $model : NULL;
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////////    CREATE MODEL METHOD AREA END
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////////    GET METHOD AREA START
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    protected function getModelName($model){
        return strtolower(class_basename($model));
    }
    protected function getModelId($model){
        return $model->id;
    }
    protected function getResizeImages($file){
        $imageMake = Intervention::make($file);
        $mime = $imageMake->mime();
        $image = [];
        foreach( $this->responsiveResolution as $key => $value ){
            $img = $imageMake->widen((int)$value);
            if($value == '30'){$img->blur(2);}
            $image[$value]['image'] = $img->stream(null,100);
            $image[$value]['mime'] = $mime;
        }
        $imageMake->destroy();
        return $image;
    }
    protected function getInternalS3Url($path){
        $explode = explode($this->storagePath,$path);
        return $explode[1];
    }
    protected function getFileType($value){
        if( gettype($value) === 'object' ) return "file";
        $file = $value['file'];
        if( $this->isBase64($file) ){ return "base64"; }
        else if( $this->isUrl($file) ){ return "url"; }
        else if( is_null($file) ){ return null; }
        return Abort::Error('0050',"Unknown file data");
    }
    protected function getExtension($value){
        return $value;
    }
    public function getId(){
        if( $this->needUpload ) return null;
        return $this->isGroup
            ? $this->createModel['id']
            : $this->createModel[0]['id'];
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////////    GET METHOD AREA END
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////////    SET METHOD AREA START
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    private function setBasicVariable($model,$inputFile){
        $this->model = $model;
        $this->inputFile = $inputFile;
    }
    protected function setToArray($inputFile){
        if( gettype($inputFile) === 'object' && get_class($inputFile) === 'Illuminate\Http\UploadedFile' ){
            $checker[] = $inputFile;
        }else if( isset($inputFile['file']) ) // if file is alone... grep into array
            $checker[] = $inputFile;
        return isset($checker) ? $checker : $inputFile;
    }
    protected function setRandomFileName(){
        return Str::random(30);
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////////    SET METHOD AREA END
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////////    VALID METHOD AREA START
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    protected function ownerCheck($fileObj){
        $ownerCheck = null;
        if( isset($fileObj[$this->ownCheckers['camel']]) ){
            $ownerCheck = $fileObj[$this->ownCheckers['camel']] ? true : false ;
        }else if( $fileObj['type'] == 'base64' ){
            $ownerCheck = true;
        }else{
            $ownerCheck = false;
        }
        return $ownerCheck;
    }
    private function checkNeedUpload($inputFile){
        if( gettype($inputFile) === 'object' ){
            return false;
        }else{
            if(is_null($inputFile['file'])) return true;
        }
        return false;
    }
    protected function findGroupExist($inputFile){
        $groupId = null;
        foreach( $inputFile as $key => $value ){
            if( isset($value['id']) ){
                $findGroupId = Image::findOrFail($value['id'])->imageGroup->id;
                if( !is_null($groupId) && $groupId != $findGroupId ) Abort::Error('0040',"has different group id");
                $groupId = $findGroupId;
            }
        }
        return $groupId;
    }
    protected function fileTypeCheck($inputFile){
        foreach( $inputFile as $key => $value ){
            $fileType = $this->getFileType($value);
            if( $fileType === 'file' ){
                $inputFile[$key]->type = $fileType;
            }else{
                $inputFile[$key]['type'] = $fileType;
            }
        }
        return $inputFile;
    }
    protected function isBase64($file){
        $explodeBase64 = explode('data:image/',$file);
        return count($explodeBase64) > 1;
    }
    protected function isUrl($file){
        $explodeBase64 = explode('http',$file);
        return count($explodeBase64) > 1;
    }
    protected function isGrouping($inputFile){
        return !isset($inputFile['file']);
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////////    VALID METHOD AREA END
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
}
