<?php
/**
 * Class to operate with files
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 */
class File {

    //--constants of class--//
    const FILE_TEST = 1;
    const FILE_RESULT = 2;
    const FILE_NOT_CREATED = 0;
    const FILE_CREATED = 1;
    const FILE_OVERWRITTEN = 2;
    const FILE_NOT_OVERWRITTEN = 3;

    //--properties of class--//
    private $_path;
    private $_fileName;
    private $_file;

    /**
     * Constructor of class
     */
    public function __construct($fileName = null, $fileType = self::FILE_TEST) {
        $this->_setFileType($fileType);
        $this->_fileName = $fileName;
        $this->_file = null;
    }
    
    //--begin setters & getters--//
    /**
     * Sets the path of file depending on your type: test or result
     * 
     * @param int $fileType
     * @return void
     */
    public function _setFileType($fileType) {
        $fileDir = $fileType == self::FILE_RESULT ? "result_files" : "test_files";
        $this->_path = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $fileDir . DIRECTORY_SEPARATOR;
    }

    /**
     * Sets the name of file
     * 
     * @param string $fileName
     * @return void
     */
    public function _setFileName($fileName) {
        $this->_fileName = $fileName;
    }

    /**
     * Gets the name of file
     * 
     * @return string
     */
    public function _getFileName() {
        return $this->_fileName;
    }

    /**
     * Gets the path and name of file
     * 
     * @return string
     */
    public function _getFullFileName() {
        return $this->_path . $this->_fileName;
    }

    /**
     * Checks if the name of file is empty
     * 
     * @return bool
     */
    public function _isEmptyFileName() {
        return empty($this->_fileName);
    }
    //--end setters & getters--//

    /**
     * Checks if the file exists
     * 
     * @return bool
     */
    public function exists() {
        return file_exists($this->_getFullFileName());
    }

    /**
     * Creates a text file for writing
     * 
     * @return resource of file
     */
    public function create() {
        $this->_file = fopen($this->_getFullFileName(), "wt");
        return $this->_file;
    }

    /**
     * Opens a text file for reading
     * 
     * @return resource of file
     */
    public function load() {
        $this->_file = fopen($this->_getFullFileName(), "rt");
        return $this->_file;
    }

    /**
     * Closes a file
     * 
     * @return void
     */
    public function close() {
        fclose($this->_file);
        $this->_file = null;
    }

    /**
     * Writes a new line to a text file
     * 
     * @param string $string String to write
     * @return int
     */
    public function writeln($string) {
        return fwrite($this->_file, $string . "\n");
    }

    /**
     * Flushes the output to a file
     * 
     * @return bool
     */
    public function flush() {
        return fflush($this->_file);
    }

    /**
     * Reads a line from a text file; it returns true if EOF is reached
     * 
     * @return mixed
     */
    public function readln() {
        $testLine = fgets($this->_file);
        return ($testLine !== false) ? trim($testLine) : false;
    }
}
?>