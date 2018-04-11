<?php
namespace lib;
use Exception;


class Filemanager
{
    /**
     * Check the exist filename
     *
     * @param string $fileName The full path of the filename
     * @return bool
     */
    public function isFileExist($fileName)
    {
        if (is_file($fileName) && stream_is_local($fileName)) {
            return true;
        }
        return false;
    }

    /**
     * Check the exist directory
     *
     * @param string $dirName The full path of the directory
     * @return bool
     */
    public function isDirectoryExist($dirName)
    {
        $dirNamePath = dirname($dirName);
        if (is_dir($dirNamePath)){
            return true;
        }
        return false;
    }

    /**
     *  Convert input variable to array
     *
     * @param string|array $files
     * @return array
     * @throws Exception When input $files is differ than string and array
     */
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

    /**
     *  Copy file or directory
     *
     * @param string $fromFile The file source from full path
     * @param string $toFile The file destination full path
     *
     * @return void
     * @throws Exception When cannot copy file source, or when cannon write file destination
     */
    public function copyFile($fromFile, $toFile) {

        if (true !== $this->isFileExist($fromFile)) {
            throw new Exception(printf('File "%s" not exist', $fromFile));
        }

        if (true !== $this->isDirectoryExist($toFile)) {
            $this->createDir(basename($toFile));
        }

        if (false === $sourceFromFile = @fopen($fromFile, 'r')) {
            throw new Exception(sprintf('Failed : Cannot open file  "%s" copy to copy.', $fromFile));
        }
        if (false === $sourceToFrom = @fopen($toFile, 'w')) {
            throw new Exception(sprintf('Failed: Cannot open file "%s" to write.', $toFile));
        }
        stream_copy_to_stream($sourceFromFile, $sourceToFrom);
        fclose($sourceFromFile);
        fclose($sourceToFrom);
    }

    /**
     *  Rename dir or file
     *
     * @param string $newName The file or directory full path
     * @param string $oldName The file or directory full path
     *
     * @return void
     * @throws Exception On rename finished failure
     */
    public function changeName ($oldName, $newName){
        if (true === $this->isFileExist($oldName) || true === $this->isDirectoryExist($oldName))
        {
            if (true !== @rename($newName, $oldName)) {
                throw new Exception(sprintf('Cannot rename "%s" to "%s".', $oldName, $newName));
            }
        }   
    }

    /**
     * Delete directory
     * @param string|array $dirs The full path to directories to delete
     * @throws Exception
     */
    public function deleteDirectory($dirs)
    {
        foreach ($this->toArray($dirs) as $dir){
            if ($this->isDirectoryExist($dir)){
                $files = array_diff(scandir($dir), array('.','..'));
                foreach ($files as $file) {
                    (is_dir("$dir/$file")) ? $this->deleteDirectory("$dir/$file") : $this->deleteFiles("$dir/$file");
                }
            }
        }
    }

    /**
     * Delete file
     * @param string|array $files The full path to file to delete
     *
     * @return void
     * @throws Exception
     */
    public function deleteFiles($files)
    {
        foreach ($this->toArray($files) as $file) {
            if ($this->isFileExist($file)) {
                if (true !== unlink($file)) {
                    throw new Exception(printf('Cannot delete "$s"', $file));
                }
            }
        }
    }

    /**
     * @param string|array $dirs Create new directory
     * @param int $mode Unix
     * @throws Exception
     */
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
