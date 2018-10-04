<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\tests\helpers\conversion\Expect;
use VovanVE\HtmlTemplate\tests\helpers\conversion\ExpectRuntimeThrow;
use VovanVE\HtmlTemplate\tests\helpers\conversion\ExpectSuccess;
use VovanVE\HtmlTemplate\tests\helpers\conversion\ExpectThrow;

trait StringConversionTestTrait
{
    /**
     * @param string $path
     * @param string $extension
     * @return iterable
     */
    protected function expectationDataProvider(string $path, string $extension): iterable
    {
        $ext = strlen($extension);

        /** @var \RecursiveIteratorIterator|\RecursiveDirectoryIterator $it */
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path)
        );

        foreach ($it as $filepath => $file) {
            /** @var \SplFileInfo $file */
            if ($file->isFile() && substr($file->getBasename(), -$ext) === $extension) {
                $sub_path_name = $it->getSubPathname();
                yield [
                    $this->createExpectFromFile($filepath, $sub_path_name),
                    $sub_path_name
                ];
            }
        }
    }

    /**
     * @param string $filename
     * @param string $message
     * @return Expect
     */
    protected function createExpectFromFile(string $filename, string $message): Expect
    {
        if (!file_exists($filename)) {
            throw new \InvalidArgumentException("File does not exist: $filename");
        }

        $content = file_get_contents($filename);
        if (false === $content) {
            throw new \InvalidArgumentException("Cannot read file: $filename");
        }

        return $this->createExpectFromContent($content, $message);
    }

    /**
     * @param string $content
     * @param string $message
     * @return Expect
     */
    protected function createExpectFromContent(string $content, string $message): Expect
    {
        if ( '' !== $content && "\n" === $content[-1]) {
            $content = substr($content, 0, -1);
        }

        $re = <<<'_REGEXP'
/
    (?: ^ | \n )
    ---- \h ([A-Z]+) (%?) \h ----
    (?: $ | \n )
/Dx
_REGEXP;
        $parts = preg_split($re, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

        $input = array_shift($parts);
        $blocks = [];
        while ($parts) {
            $type = array_shift($parts);
            $isFormat = (bool)array_shift($parts);
            $value = array_shift($parts);

            if (isset($blocks[$type])) {
                throw new \LogicException("Duplicate block '$type'");
            }
            $blocks[$type] = [$isFormat, $value];
        }

        ksort($blocks);

        $keys = join(',', array_keys($blocks));
        switch ($keys) {
            case 'CODE,RESULT':
                [$codeIsFormat, $code] = $blocks['CODE'];
                [$resultIsFormat, $result] = $blocks['RESULT'];
                return new ExpectSuccess($message, $input, $codeIsFormat, $code, $resultIsFormat, $result);

            case 'CODE,THROW':
                [$codeIsFormat, $code] = $blocks['CODE'];
                [$throwIsFormat, $throw] = $blocks['THROW'];
                return new ExpectRuntimeThrow($message, $input, $codeIsFormat, $code, $throwIsFormat, $throw);

            case 'THROW':
                return new ExpectThrow($input, ...$blocks['THROW']);

            default:
                throw new \InvalidArgumentException("Unknown set of blocks: $keys. Target: $message");
        }
    }
}
