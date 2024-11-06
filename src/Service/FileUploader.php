<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private string $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        // Génère un nom de fichier unique
        $fileName = uniqid('music_') . '.' . $file->guessExtension();

        // Déplace le fichier dans le répertoire de destination
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de l\'upload du fichier : ' . $e->getMessage());
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
