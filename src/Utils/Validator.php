<?php

namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * This class is used to provide an example of integrating simple classes as
 * services into a Symfony application.
 *
 */
class Validator
{
    public function validatePath(?string $path): string
    {
        if (empty($path)) {
            throw new InvalidArgumentException('The path can not be empty.');
        }

        if (is_dir($path)) {
            throw new FileNotFoundException('The path :'.$path.' is a directory. You need to enter an valide path.');
        }

        if (!file_exists($path)) {
            throw new FileNotFoundException('The file :'.$path.' does not exists!. You need to enter an valide path.');
        }

        if (pathinfo($path)['extension'] != 'csv') {
          throw new InvalidArgumentException('File extention is not valid!. You need to enter a csv file.');
        }

        return $path;
    }

}
