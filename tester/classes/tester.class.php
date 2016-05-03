<?php
/**
 * Class to tests
 * 
 * @package WorkanaHiringChallenge
 * @author Oswaldo Peña <oswaldopr@gmail.com>
 */
class Tester {

    //--constants of class--//
    const LINE_TEST = 1;
    const LINE_IGNORED = 2;
    const LINE_ERROR = 3;

    //--objects of class--//
    private $_class;
    private $_method;
    private $_file;

    //--properties of class--//
    private $_test;
    private $_result;
    private $_testsData;
    private $_lines;
    private $_testsDuration;

    /**
     * Constructor of class
     */
    public function __construct($class = null, $method = null) {
        $this->_setClass($class);
        $this->_setMethod($method);
        $this->_file = new File();
        $this->_resetTests();
        $this->_resetLines();
        $this->_testsDuration = 0;
    }

    //--begin setters & getters--//
    /**
     * Sets the class to test
     * 
     * @param mixed $class
     * @return bool
     */
    public function _setClass($class = null) {
        if(empty($class)) {
            $this->_class = null;
            return false;
        }
        elseif(is_object($class))
            $this->_class = $class;
        elseif(is_string($class))
            $this->_class = new ReflectionClass($class);
        return true;
    }

    /**
     * Sets the method to test
     * 
     * @param mixed $method
     * @return bool
     */
    public function _setMethod($method = null) {
        if(empty($method) || empty($this->_class)) {
            $this->_method = null;
            return false;
        }
        elseif(is_object($method))
            $this->_method = $method;
        elseif(is_string($method))
            $this->_method = $this->_class->getMethod($method);
        return true;
    }

    /**
     * Checks if the method can be tested
     * 
     * @return bool
     */
    private function _isTestableMethod() {
        $methodName = $this->_method->getName();
        $internalUse = ($methodName{0} == "_" || $this->_method->isConstructor() || $this->_method->isDestructor());
        return ($this->_method->isPublic() && !$internalUse);
    }

    /**
     * Gets the name of file for test or result
     * 
     * @return string
     */
    public function _getFileName() {
        return $this->_file->_getFileName();
    }

    /**
     * Sets the value of a test
     * 
     * @param string $test
     * @return void
     */
    private function _setTest($test) {
        $this->_test = $test;
    }

    /**
     * Gets the value of a test
     * 
     * @return string
     */
    private function _getTest() {
        return $this->_test;
    }

    /**
     * Checks if a test is empty
     * 
     * @return bool
     */
    private function _isEmptyTest() {
        return empty($this->_test);
    }

    /**
     * Sets the expected result of a test
     * 
     * @param mixed $result
     * @return void
     */
    private function _setResult($result) {
        $this->_result = $result;
    }

    /**
     * Gets the expected result of a test
     * 
     * @return string
     */
    private function _getResult() {
        return $this->_result;
    }

    /**
     * Resets the values involved in the tests
     * 
     * @return void
     */
    private function _resetTests() {
        $this->_test = null;
        $this->_result = null;
        $this->_testsData = [];
        //--array structure--//
        $this->_testsData[0] = ["status" => null, "test" => null, "result" => null];
    }

    /**
     * Resets the counter of lines involved in the tests
     * 
     * @return void
     */
    private function _resetLines() {
        $this->_lines = ["total" => 0, "test" => 0, "ignored" => 0, "error" => 0];
    }

    /**
     * Sets a test as applied
     * 
     * @param bool $result
     * @return void
     */
    private function _setTestApplied($result) {
        $auxResult = $result ? "true" : "false";
        $this->_testsData[] = ["status" => self::LINE_TEST, "test" => $this->_test, "result" => $auxResult];
        $this->_lines["total"]++;
        if($result)
            $this->_lines["testSuccess"]++;
        else
            $this->_lines["testFail"]++;
    }

    /**
     * Sets a test as ignored
     * 
     * @return void
     */
    private function _setTestIgnored() {
        $case = $this->_test[0] == "#" ? "Comment" : "Line Blank";
        $this->_testsData[] = ["status" => self::LINE_IGNORED, "test" => $case, "result" => null];
        $this->_lines["total"]++;
        $this->_lines["ignored"]++;
    }

    /**
     * Sets a test as error
     * 
     * @param mixed $result
     * @return void
     */
    private function _setTestError($result) {
        $this->_testsData[] = ["status" => self::LINE_ERROR, "test" => $this->_test, "result" => $result];
        $this->_lines["total"]++;
        $this->_lines["error"]++;
    }

    /**
     * Gets the data of the tests
     * 
     * @return array
     */
    private function _getTestsData() {
        return $this->_testsData;
    }

    /**
     * Gets the total lines of the tests
     * 
     * @return int
     */
    private function _getTotalLines() {
        return $this->_lines["total"];
    }

    /**
     * Gets the successful lines of the tests
     * 
     * @return int
     */
    private function _getSuccessTestLines() {
        return $this->_lines["testSuccess"];
    }

    /**
     * Gets the failed lines of the tests
     * 
     * @return int
     */
    private function _getFailedTestLines() {
        return $this->_lines["testFail"];
    }

    /**
     * Gets the ignored lines of the tests
     * 
     * @return int
     */
    private function _getIgnoredLines() {
        return $this->_lines["ignored"];
    }

    /**
     * Gets the error lines of the tests
     * 
     * @return int
     */
    private function _getErrorLines() {
        return $this->_lines["error"];
    }

    /**
     * Sets the elapsed time for the tests (in milliseconds)
     * 
     * @param int $time
     * @return void
     */
    private function _setTestsDuration($time) {
        $this->_testsDuration = $time;
    }

    /**
     * Gets the elapsed time for the tests (in milliseconds)
     * 
     * @return int
     */
    private function _getTestsDuration() {
        return $this->_testsDuration;
    }
    //--end setters & getters--//

    /**
     * Sets the name of a file for tests or results
     * 
     * @return bool
     */
    private function createFileName() {
        if(empty($this->_class) || empty($this->_method)) {
            $this->_file->_setFileName("");
            return false;
        }
        $className = strtolower($this->_class->getName());
        $methodName = strtolower($this->_method->getName());
        $this->_file->_setFileName("$className.$methodName.dat");
        return true;
    }

    /**
     * Creates a test file
     * 
     * @param bool $overwrite Overwrite file if exists
     * @return int
     */
    public function createTestFile($overwrite = true) {
        if(!$this->_isTestableMethod() || !$this->createFileName())
            return File::FILE_NOT_CREATED;
        $existingFile = $this->_file->exists();
        if(!$overwrite && $existingFile)
            return File::FILE_NOT_OVERWRITTEN;
        $this->_file->create();
        $this->writeHeaderTestFile();
        $this->writeStructureTestFile();
        $this->writeMethodReturnValue();
        $this->_file->close();
        return (!$existingFile) ? File::FILE_CREATED : File::FILE_OVERWRITTEN;
    }

    /**
     * Writes the header for a test file
     * 
     * @return void
     */
    private function writeHeaderTestFile() {
        $className = $this->_class->getName();
        $methodName = $this->_method->getName();
        $this->_file->writeln("# TEST FILE FOR METHOD $className::$methodName()");
        $this->_file->writeln("#");
        $this->_file->writeln("# Here, you can define test values and expected values as result of test.");
        $this->_file->writeln("# If you want you can delete these comments for the test.");
        $this->_file->writeln("# Each line represents a test to apply. You can write as many lines as you want. Blank lines are ignored.");
        $this->_file->writeln("# The arguments must be separated by sign &: arg1&arg2&...&argN");
        $this->_file->writeln("# The result expected must be written at the end of line and begin with a equal sign: arg1&arg2=result");
        $this->_file->writeln("#");
        $this->_file->flush();
    }

    /**
     * Writes the structure for a test file
     * 
     * @param bool $resultFile Result file (if true only prints the list of parameters)
     * @return void
     */
    private function writeStructureTestFile($resultFile = false) {
        if(!$resultFile) {
            $this->_file->writeln("# STRUCTURE OF TESTS LINES");
            $this->_file->writeln("#");
            $this->_file->writeln("# For strings: \"some literal string\" or \"\" (empty string)");
            $this->_file->writeln("# For arrays: [index1=>value1,index2=>value2] or [[index11=>value11],[index21=>value21]]");
            $this->_file->writeln("# For boolean values: arg1=false or arg1&arg2=true or arg1&true=true");
            $this->_file->writeln("# If no parameters or no result: null=80 or arg1&arg2=null");
            $this->_file->writeln("# If you want to test with values by default: arg1&~&arg3&~=true or ~&~&~&~=false");
            $this->_file->writeln("# IMPORTANT: If there are more or fewer parameters than expected then the test line will be discarded.");
            $this->_file->writeln("#");
        }
        $this->_file->writeln(sprintf("# %-21s %-5s %-8s Default Value", "Parameter", "Array", "Optional"));
        if($this->_method->getNumberOfParameters() > 0) {
            $parametersList = $this->_method->getParameters();
            foreach($parametersList as $parameter) {
                $isArray = $parameter->isArray() ? "yes" : "no";
                $isOptional = "no";
                $defaultValue = "N/A";
                if($parameter->isOptional()) {
                    $isOptional = "yes";
                    $defaultValue = '"' . $parameter->getDefaultValue() . '"';
                    if($parameter->getDefaultValue() === null)
                        $defaultValue = "null";
                    elseif($parameter->getDefaultValue() === false)
                        $defaultValue = "false";
                    elseif($parameter->getDefaultValue() === true)
                        $defaultValue = "true";
                }
                $this->_file->writeln(sprintf("#  %-21s %-5s %-8s %s", $parameter->getName(), $isArray, $isOptional, $defaultValue));
            }
        }
        else
            $this->_file->writeln("# -No parameters for this method-");
        $this->_file->flush();
    }

    /**
     * Writes the value returned by a method in a test file
     * 
     * @return void
     */
    private function writeMethodReturnValue() {
        $testClass = new ReflectionClass($this->_class->getName() . "Test");
        $testObject = $testClass->newInstance();
        $returnValue = $testObject->_getReturnValue($this->_method->getName());
        unset($testObject);
        unset($testClass);
        if(empty($returnValue))
            return;
        $this->_file->writeln("#");
        $this->_file->writeln("# Return Value");
        $this->_file->writeln("#  $returnValue");
        $this->_file->flush();
    }

    /**
     * Tests a method
     * 
     * @param string $name Name of method to test
     * @param string $returnValueLiteral Literal of the value returned by the method
     * @param string $returnValueType Type of the value returned by the method
     * @return void
     */
    protected function testMethod($name = "", $returnValueLiteral = "", $returnValueType = "") {
        if(empty($returnValueType))
            $returnValueType = "string";
        if($this->applyTest($name, $returnValueType))
            $this->showResults($returnValueLiteral);
    }

    /**
     * Applies the tests for a method
     * 
     * @param string $methodName Name of the method to test
     * @param string $methodReturnValueType Type of the value returned by the method
     * @return bool
     */
    private function applyTest($methodName, $methodReturnValueType) {
        if(!$this->initializeTests($methodName))
            return false;
        echo "Applying tests...";
        $timeStart = microtime(true);
        $this->_file->load();
        ini_set("track_errors", 1);
        while($this->readTestLine() !== false) {
            $args = $this->parseTestLine($methodReturnValueType);
            if($args === false)
                continue;
            $resultExpected = $this->_getResult();
            $resultMethod = null;
            ob_start();
            if($args === null)
                $resultMethod = $this->_method->invoke($this->_class->newInstance());
            else
                $resultMethod = $this->_method->invokeArgs($this->_class->newInstance(), $args);
            ob_end_clean();
            $resultTest = assert($resultExpected === $resultMethod);
            $this->_setTestApplied($resultTest);
        }
        ini_set("track_errors", 0);
        $this->_file->close();
        $timeEnd = microtime(true);
        $this->_setTestsDuration((int)($timeEnd - $timeStart) * 1000);
        echo " OK" . PHP_EOL;
        return true;
    }

    /**
     * Initializes the tests
     * 
     * @param string $methodName Name of the method to test
     * @return bool
     */
    private function initializeTests($methodName) {
        $auxMethodName = $this->_class->getName() . "::$methodName()";
        $message = "Initializing tests for $auxMethodName... ";
        if(!$this->_setMethod($methodName)) {
            echo "{$message}Aborting: Can't load method $auxMethodName...!" . PHP_EOL;
            return false;
        }
        if(!$this->_isTestableMethod()) {
            echo "{$message}Aborting: Method $auxMethodName is not testable!" . PHP_EOL;
            return false;
        }
        if(!$this->createFileName()) {
            echo "{$message}Aborting: Error creating file name!" . PHP_EOL;
            return false;
        }
        if(!$this->_file->exists()) {
            echo "{$message}Aborting: Test file not found!" . PHP_EOL;
            return false;
        }
        echo "{$message}OK" . PHP_EOL;
        return true;
    }

    /**
     * Reads a test line
     * 
     * @return bool
     */
    private function readTestLine() {
        $line = $this->_file->readln();
        if($line === false)
            return false;
        $this->_setTest($line);
        if(empty($line) || $line{0} == "#") {
            $this->_setTestIgnored();
            $this->_setTest("");
        }
        return true;
    }

    /**
     * Parses a test line
     * 
     * @param string $methodReturnValueType Type of the value returned by the method
     * @return mixed
     */
    private function parseTestLine($methodReturnValueType) {
        if($this->_isEmptyTest())
            return false;
        list($args, $result) = explode("=", $this->replaceControlCharacters($this->_getTest()));
        if(!$this->evalResult($result, $methodReturnValueType))
            return false;
        $numberOfParameters = $this->_method->getNumberOfParameters();
        if($numberOfParameters == 0)
            return null;
        $args = explode("&", $args);
        if(count($args) != $numberOfParameters) {
            $this->_setTestError("Error in arguments: bad number of parameters");
            return false;
        }
        $args = $this->evalParameters($args);
        return (!$args) ? false : $args;
    }

    /**
     * Replaces control characters for parsing
     * 
     * @param string $string
     * @return string
     */
    private function replaceControlCharacters($string) {
        $string = trim($string);
        $length = strlen($string);
        $doubleQuotes = false;
        $squareBrackets = 0;
        $auxString = "";
        for($i = 0; $i < $length; $i++) {
            $char = $string{$i};
            if($char == '[' || $char == '(')
                $squareBrackets++;
            elseif($char == ']' || $char == ')')
                $squareBrackets--;
            $isDoubleQuotes = ($char == '"');
            $doubleQuotes = $doubleQuotes xor $isDoubleQuotes;
            if($isDoubleQuotes) {
                $auxString .= "|QUOTES|";
                continue;
            }
            if($doubleQuotes || $squareBrackets > 0) {
                if($char == '&')
                    $auxString .= "|AMP|";
                elseif($char == '=')
                    $auxString .= "|EQUAL|";
                else
                    $auxString .= $char;
            }
            else
                $auxString .= $char;
        }
        return $auxString;
    }

    /**
     * Reverts replacement of control characters
     * 
     * @param string $string
     * @return string
     */
    private function revertReplaceControlCharacters($string) {
        $string = str_replace("|QUOTES|", '"', $string);
        $string = str_replace("|AMP|", '&', $string);
        $string = str_replace("|EQUAL|", '=', $string);
        return $string;
    }

    /**
     * Evaluates the expected result for a test
     * 
     * @param string $expression Expected result for a test
     * @param string $methodReturnValueType Type of the value returned by the method
     * @return bool
     */
    private function evalResult($expression, $methodReturnValueType) {
        $expression = $this->revertReplaceControlCharacters($expression);
        if(@eval("\$result = $expression;") === false) {
            $this->_setTestError("Error in result: $php_errormsg");
            return false;
        }
        $isTypeFunction = "$methodReturnValueType";
        if(!$isTypeFunction($result)) {
            $this->_setTestError("Error in result: it's not of type $methodReturnValueType");
            return false;
        }
        $this->_setResult($result);
        return true;
    }

    /**
     * Evaluates the parameters necessary for a method that will be tested
     * 
     * @param array $args Array of arguments
     * @return array
     */
    private function evalParameters($args) {
        $parametersList = $this->_method->getParameters();
        foreach($parametersList as $index => $parameter) {
            $parameterName = $parameter->getName();
            $parameterIndex = $index + 1;
            $args[$index] = $this->revertReplaceControlCharacters($args[$index]);
            if($args[$index] == "~" && $parameter->isOptional()) {
                $args[$index] = $parameter->getDefaultValue();
                continue;
            }
            elseif($args[$index] == "~") {
                $this->_setTestError("Error in argument #$parameterIndex ($parameterName): it's not optional");
                return false;
            }
            if(@eval("\$value = $args[$index];") === false) {
                $this->_setTestError("Error in argument #$parameterIndex ($parameterName): $php_errormsg");
                return false;
            }
            if($parameter->isArray() && !is_array($value)) {
                $this->_setTestError("Error in argument #$parameterIndex ($parameterName): it's not an array");
                return false;
            }
            $args[$index] = $value;
        }
        return $args;
    }

    /**
     * Shows the results of tests
     * 
     * @param string $methodReturnValueLiteral Literal of the value returned by the method
     * @return void
     */
    private function showResults($methodReturnValueLiteral) {
        echo "Results..." . PHP_EOL;
        $testsSuccess = $this->_getSuccessTestLines();
        $testsFailed = $this->_getFailedTestLines();
        echo "Tests: " . ($testsSuccess + $testsFailed) . " (Success: $testsSuccess, Failure: $testsFailed)" . PHP_EOL;
        echo "Ignored: " . $this->_getIgnoredLines() . PHP_EOL;
        echo "Error: " . $this->_getErrorLines() . PHP_EOL;
        echo PHP_EOL . "Duration: " . $this->_getTestsDuration() . " ms" . PHP_EOL;
        if($this->createResultFile($methodReturnValueLiteral))
            echo "See the result file '" . $this->_file->_getFileName() . "' for more details..." . PHP_EOL . PHP_EOL;
    }

    /**
     * Creates a file with results of tests
     * 
     * @param mixed $methodReturnValueLiteral Literal of the value returned by the method
     * @return bool
     */
    private function createResultFile($methodReturnValueLiteral) {
        $this->_file->_setFileType(File::FILE_RESULT);
        if(!$this->createFileName())
            return false;
        $this->_file->create();
        $this->writeHeaderResultFile($methodReturnValueLiteral);
        $this->writeResults();
        $this->_file->close();
        return true;
    }

    /**
     * Writes the header for a result file
     * 
     * @param mixed $methodReturnValueLiteral Literal of the value returned by the method
     * @return void
     */
    private function writeHeaderResultFile($methodReturnValueLiteral) {
        $className = $this->_class->getName();
        $methodName = $this->_method->getName();
        $this->_file->writeln("# TEST RESULT FILE FOR METHOD $className::$methodName()");
        $this->_file->writeln("#");
        $this->writeStructureTestFile(true); //print (structure) parameters
        $this->_file->writeln("#");
        $this->_file->writeln("# Return Value");
        $this->_file->writeln("#  $methodReturnValueLiteral");
        $this->_file->writeln("");
        $this->_file->flush();
    }

    /**
     * Writes the results of tests in a result file
     * 
     * @return void
     */
    private function writeResults() {
        $this->_file->writeln("Total Lines Read: " . $this->_getTotalLines());
        $this->_file->writeln("* Ignored Lines: " . $this->_getIgnoredLines());
        $this->_file->writeln("* Error Lines:   " . $this->_getErrorLines());
        $this->_file->writeln("* Failed Tests:  " . $this->_getFailedTestLines());
        $this->_file->writeln("* Success Tests: " . $this->_getSuccessTestLines());
        $this->_file->writeln("");
        $spaces = 9;
        $this->_file->writeln(sprintf("%-{$spaces}s TEST/DESCRIPTION", "STATUS"));
        $this->_file->writeln(sprintf("%-{$spaces}s ================", "======"));
        $data = $this->_getTestsData();
        foreach($data as $test) {
            if($test["status"] == self::LINE_TEST)
                $this->_file->writeln(sprintf("%-{$spaces}s %s => %s", "Tested", $test["result"], $test["test"]));
            elseif($test["status"] == self::LINE_IGNORED)
                $this->_file->writeln(sprintf("%-{$spaces}s %s", "Ignored", $test["test"]));
            elseif($test["status"] == self::LINE_ERROR)
                $this->_file->writeln(sprintf("%-{$spaces}s %s => %s", "Error", $test["result"], $test["test"]));
        }
        $this->_file->flush();
    }

    /**
     * Sets the assert options
     * 
     * @return void
     */
    protected function setAssertOptions() {
        assert_options(ASSERT_ACTIVE, true);
        assert_options(ASSERT_WARNING, false);
        assert_options(ASSERT_BAIL, false);
        assert_options(ASSERT_QUIET_EVAL, true);
    }
}
?>