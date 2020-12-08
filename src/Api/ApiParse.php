<?php
/**
 * Entidade responsável por representar fazer o parse dos dados vindos da Api
 * para uso no sistema.
 * 
 * @package DesafioWiser
 * @subpackage Api
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

namespace DesafioWiser\Api;

class ApiParse
{
    /**
     * Realiza o parse de uma lista de arquivos.
     * 
     * @param stdClass $FilesResult
     * @return \stdClass
     */
    public static function parseFiles(\stdClass $FilesResult) : \stdClass
    {
        $retorno = new \stdClass();

        $retorno->total = 0;
        $retorno->files = [];

        foreach ($FilesResult->entries as $file) {
            if ($file->type === 'file') {
                $fileObj = new \stdClass();
                $fileObj->id   = $file->id;
                $fileObj->name = $file->name;
                $fileObj->type = $file->type;

                array_push($retorno->files, $fileObj);
                $retorno->total++;
            }
        }

        return $retorno;
    }

    /**
     * Realiza o parse de uma lista de diretórios.
     * 
     * @param stdClass $FoldersResult
     * @return \stdClass
     */
    public static function parseFolders(\stdClass $FoldersResult) : \stdClass
    {
        $retorno = new \stdClass();

        $retorno->total = 0;
        $retorno->folders = [];

        if ( isset($FoldersResult->item_collection->entries) ) {
            foreach ($FoldersResult->item_collection->entries as $folder) {
                if ($folder->type === 'folder') {
                    $folderObj = new \stdClass();
                    $folderObj->id   = $folder->id;
                    $folderObj->name = $folder->name;
                    $folderObj->type = $folder->type;
    
                    array_push($retorno->folders, $folderObj);
                    $retorno->total++;
                }
            }
        }

        return $retorno;
    }
}