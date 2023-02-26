<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Loguer;

use App\Contracts\Loguer;
use App\Contracts\LoguerEntry;
use App\Contracts\User;
use App\Decorators\FileDecorator;

class File implements Loguer
{
    protected $file;

    public function __construct()
    {
        $this->file = __DIR__ . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'notification.json';

    }
    public function add(User $user, string $message, string $channel): string
    {
        $fileContent = file_get_contents($this->file);
        
        $rand = time() * rand(1, time());
        $id = $rand . substr_count($fileContent, PHP_EOL);
        
        $date = (new \DateTime())->format('Y-m-d H:i:s');

        $entry = (new FileDecorator(
            $id, $user->name(), $message, $channel, $rand % 2 === 0 ? 'Sent' : 'Failed', $date
        ))->__toArray();
        
        $jsonEntry = json_encode($entry);
        $fileEntry = $jsonEntry . PHP_EOL . $fileContent;
        $logFile = fopen($this->file, "w") or die("Unable to open file!");
        fwrite($logFile, $fileEntry);
        fclose($logFile);
        return $id;
    }

    public function get(string $id): LoguerEntry
    {
    }

    public function all(): array
    {
        $fileContent = file_get_contents($this->file);
        return explode(PHP_EOL, $fileContent);
    }
}
