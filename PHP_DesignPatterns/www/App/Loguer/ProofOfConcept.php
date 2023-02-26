<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Loguer;

use App\Contracts\User;

/**
 * This class is not intend as LOG
 * the only reason is to prove that the chosen Notification mode has been correctly invoked
 */

class ProofOfConcept
{
    public function __construct(private User $user, private string $message, private string $channel)
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'proofOfConcept.txt';
        $contents = file_get_contents($file);
        $entry = $contents . $user->name() . ' | ' . $this->message . ' | ' . $this->channel . ' | ' . (new \DateTime())->format('Y-m-d H:i:s') . PHP_EOL;
        $proofOfConcept = fopen($file, "w") or die("Unable to open file!");
        fwrite($proofOfConcept, $entry);
        fclose($proofOfConcept);
    }
}
