<?php

namespace App\View\Components\Reports2;

use Throwable;

trait TraitReportFormatString
{
    function evaluateAGG($expression) {
        // Regex to extract all AGG(...) patterns
        $pattern = '/AGG\((.*?)\)/';
        // Use preg_replace_callback to replace each AGG(...) with evaluated result
        $result = preg_replace_callback($pattern, function ($matches) {
            // $matches[1] contains the inner expression of AGG(...)
            $innerExpression = $matches[1];
            // Evaluate the inner expression
            $evaluatedResult = $this->evaluateMathExpression($innerExpression);
            $evaluatedResult = str_pad($evaluatedResult, 2, '0', STR_PAD_LEFT);
            return $evaluatedResult;
        }, $expression);
        return $result;
    }
    
    function evaluateMathExpression($expression) {
        $expression = trim($expression);
        // Split the expression into tokens (numbers, strings, operators)
        $tokens = preg_split('/([\+\-\*\/])/', $expression, -1, PREG_SPLIT_DELIM_CAPTURE);
        // Validate and process each token
        $parsedTokens = array_map(function ($token) {
            $token = trim($token);
            if (is_numeric($token)) {
                // If it's a valid number, keep it as is
                return $token;
            } elseif (preg_match("/^'(.*?)'$/", $token)) {
                // If it's a string (e.g., 'abc'), mark it as an error
                return "Error: Invalid string '$token'";
            } else {
                // If it's an operator, allow it
                return $token;
            }
        }, $tokens);
        // Rebuild the validated expression
        $validExpression = implode(' ', $parsedTokens);
        try {
            // Evaluate the expression using eval if valid
            return eval('return ' . $validExpression . ';');
        } catch (Throwable $e) {
            // Return error message if the expression is invalid
            return "Error in expression: $expression";
        }
    }

    function parseVariables($sqlStr)
    {
        preg_match_all('/(?<!\\\)\{%\\s*([^}]*)\s*\%}/', $sqlStr, $parsedVariables);
        return $parsedVariables;
    }

    function formatReportHref($string, $dataLine)
    {
        $parsedVariables = $this->parseVariables($string);
        foreach (last($parsedVariables) as $key => $value) {
            $value = trim(str_replace('$', '', $value));
            if(!is_array($dataLine)) $dataLine = (array)$dataLine;
            if (isset($dataLine[$value])) {
                $valueParam =  $dataLine[$value];
                if (is_array($valueParam)) {
                    $itemsIsNumeric = array_filter($valueParam, fn($item) => is_numeric($item));
                    if (!empty($itemsIsNumeric)) $valueParam = implode(',', $valueParam);
                    else {
                        $str = "";
                        array_walk($valueParam, function ($item) use (&$str) {
                            $str .= "'" . $item . "',";
                        });
                        $valueParam = trim($str, ",");
                    }
                }
                $searchStr = head($parsedVariables)[$key];
                $string = str_replace($searchStr, $valueParam, $string);
                $string = $this->evaluateAGG($string);
            } else {
                return dd("Param '{$value}' not found in dataLine array", $string);
            }
        }
        // dd($string);
        return $string;
    }
}
