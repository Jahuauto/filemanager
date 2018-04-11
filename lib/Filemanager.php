<?php
namespace lib;
use Exception;


class Filemanager
{
    public function isFileExist($fileName)
    {
        if (is_file($fileName) && stream_is_local($fileName)) {
            return true;
        }
        return false;
    }

    public function isDirectoryExist($dirName)
    {
        (string) $dirName = basename($dirName);
        if (is_dir($dirName)){
            return true;
        }
        return false;
    }
    private function toArray($files){
        if (is_array($files)){
            return $files;
        }
        elseif (is_string($files))
        {
            return array($files);
        }
        throw new Exception('Error input data');
    }
    public function copyFile($fromFile, $toFile){
        
        if (true !== $this->isFileExist($fromFile)){
            throw new Exception(printf('File "%s" not exist',$fromFile));
        }
        
        if (true !== $this->isDirectoryExist($toFile)){
            $this->createDir(basename($toFile));
        }
        
        if (false === $sourceFromFile = @fopen($fromFile, 'r')) {
                throw new Exception(sprintf('Failed to copy "%s" to "%s" because source file could not be opened for reading.', $originFile, $targetFile));
            }

           
            if (false === $sourceToFrom = @fopen($toFile, 'w')) {
                throw new Exception(sprintf('Failed to copy "%s" to "%s" because target file could not be opened for writing.', $originFile, $targetFile));
            }          
            stream_copy_to_stream($sourceFromFile, $sourceToFrom);
            fclose($sourceFromFile);
            fclose($sourceToFrom);             
    }

    public function changeName ($oldName, $newName){
        if (true === $this->isFileExist($oldName) || true === $this->isDirectoryExist($oldName))
        {
            if (true !== @rename($newName, $oldName)) {
                throw new Exception(sprintf('Cannot rename "%s" to "%s".', $oldName, $newName));
            }
        }   
    }
    public function createDir ($dirs, $mode = 0777){
        foreach ($this->toArray($dirs) as $dir) {
            if (false === $this->isDirectoryExist($dir)){
                if (true !== @mkdir($dir, $mode, true)) {
                    throw new Exception(printf('Filed create new dir "%s"', $dir));
                }
            }
        }
    }
}
