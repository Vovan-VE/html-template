<?php
namespace VovanVE\HtmlTemplate\tests\helpers;

use VovanVE\HtmlTemplate\tests\helpers\conversion\Expect;
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
        $re = <<<'_REGEXP'
/
    ^
    (?<source>
        (?:
            [^\n]++
        |
            \n (?! ---- \h [A-Z]+ %? \h ----\n)
        )*+
    )
    \n
    ---- \h (?<type> [A-Z]+ ) (?<format> %? ) \h ---- \n
    (?<result>
        .*+
    )
    $
/Dxs

_REGEXP;
        if (!preg_match($re, $content, $match)) {
            throw new \InvalidArgumentException('Invalid format');
        }

        $source = $match['source'];
        $type = $match['type'];
        $isFormat = (bool)$match['format'];
        $result = $match['result'];

        if ('' !== $result && "\n" === $result[-1]) {
            $result = substr($result, 0, -1);
        }

        switch ($type) {
            case 'OK':
                return new ExpectSuccess($source, $result, $isFormat, $message);

            case 'THROW':
                return new ExpectThrow($source, $isFormat, $result);

            default:
                throw new \InvalidArgumentException("Unknown type '$type'");
        }
    }
}
