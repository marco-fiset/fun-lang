<?php

use Fun\Lexing\Tokens\Token;
use Fun\Lexing\Tokens\TokenType;
use Fun\Parsing\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function testCanParseSingleNumberExpression()
    {
        $tokens = [
            new Token('3', TokenType::Number),
            new Token(';', TokenType::Terminator)
        ];

        $node = $this->parse($tokens);

        $this->assertInstanceOf('Fun\Parsing\Nodes\InstructionListNode', $node);

        $expr = $node->getInstructions()[0];
        $this->assertNumberNode($expr->getLeft(), 3);
    }

    public function testCanParseBinaryExpression()
    {
        $tokens = [
            new Token('3', TokenType::Number),
            new Token('+', TokenType::Operator),
            new Token('5', TokenType::Number),
            new Token(';', TokenType::Terminator)
        ];

        $node = $this->parse($tokens);

        $this->assertInstanceOf('Fun\Parsing\Nodes\InstructionListNode', $node);

        $expr = $node->getInstructions()[0];

        $this->assertNumberNode($expr->getLeft(), 3);
        $this->assertEquals('+', $expr->getOperator());
        $this->assertNumberNode($expr->getRight(), 5);
    }

    private function parse($tokens)
    {
        $parser = new Parser();
        return $parser->parse($tokens);
    }

    private function assertNumberNode($node, $value)
    {
        $this->assertInstanceOf('Fun\Parsing\Nodes\NumberNode', $node);
        $this->assertEquals($value, $node->getValue());
    }
}