<?php

/*
 * Generated from Grammar.g4 by ANTLR 4.13.2
 */

use Antlr\Antlr4\Runtime\Tree\ParseTreeListener;

/**
 * This interface defines a complete listener for a parse tree produced by
 * {@see GrammarParser}.
 */
interface GrammarListener extends ParseTreeListener {
	/**
	 * Enter a parse tree produced by the `Program`
	 * labeled alternative in {@see GrammarParser::p()}.
	 * @param $context The parse tree.
	 */
	public function enterProgram(Context\ProgramContext $context): void;
	/**
	 * Exit a parse tree produced by the `Program` labeled alternative
	 * in {@see GrammarParser::p()}.
	 * @param $context The parse tree.
	 */
	public function exitProgram(Context\ProgramContext $context): void;
	/**
	 * Enter a parse tree produced by the `AssignStatement`
	 * labeled alternative in {@see GrammarParser::stmt()}.
	 * @param $context The parse tree.
	 */
	public function enterAssignStatement(Context\AssignStatementContext $context): void;
	/**
	 * Exit a parse tree produced by the `AssignStatement` labeled alternative
	 * in {@see GrammarParser::stmt()}.
	 * @param $context The parse tree.
	 */
	public function exitAssignStatement(Context\AssignStatementContext $context): void;
	/**
	 * Enter a parse tree produced by the `PrintStringStatement`
	 * labeled alternative in {@see GrammarParser::stmt()}.
	 * @param $context The parse tree.
	 */
	public function enterPrintStringStatement(Context\PrintStringStatementContext $context): void;
	/**
	 * Exit a parse tree produced by the `PrintStringStatement` labeled alternative
	 * in {@see GrammarParser::stmt()}.
	 * @param $context The parse tree.
	 */
	public function exitPrintStringStatement(Context\PrintStringStatementContext $context): void;
	/**
	 * Enter a parse tree produced by the `PrintStatement`
	 * labeled alternative in {@see GrammarParser::stmt()}.
	 * @param $context The parse tree.
	 */
	public function enterPrintStatement(Context\PrintStatementContext $context): void;
	/**
	 * Exit a parse tree produced by the `PrintStatement` labeled alternative
	 * in {@see GrammarParser::stmt()}.
	 * @param $context The parse tree.
	 */
	public function exitPrintStatement(Context\PrintStatementContext $context): void;
	/**
	 * Enter a parse tree produced by the `IfStatement`
	 * labeled alternative in {@see GrammarParser::stmt()}.
	 * @param $context The parse tree.
	 */
	public function enterIfStatement(Context\IfStatementContext $context): void;
	/**
	 * Exit a parse tree produced by the `IfStatement` labeled alternative
	 * in {@see GrammarParser::stmt()}.
	 * @param $context The parse tree.
	 */
	public function exitIfStatement(Context\IfStatementContext $context): void;
	/**
	 * Enter a parse tree produced by {@see GrammarParser::block()}.
	 * @param $context The parse tree.
	 */
	public function enterBlock(Context\BlockContext $context): void;
	/**
	 * Exit a parse tree produced by {@see GrammarParser::block()}.
	 * @param $context The parse tree.
	 */
	public function exitBlock(Context\BlockContext $context): void;
	/**
	 * Enter a parse tree produced by the `CondOrExpr`
	 * labeled alternative in {@see GrammarParser::cond()}.
	 * @param $context The parse tree.
	 */
	public function enterCondOrExpr(Context\CondOrExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `CondOrExpr` labeled alternative
	 * in {@see GrammarParser::cond()}.
	 * @param $context The parse tree.
	 */
	public function exitCondOrExpr(Context\CondOrExprContext $context): void;
	/**
	 * Enter a parse tree produced by the `CondAndPass`
	 * labeled alternative in {@see GrammarParser::cond()}.
	 * @param $context The parse tree.
	 */
	public function enterCondAndPass(Context\CondAndPassContext $context): void;
	/**
	 * Exit a parse tree produced by the `CondAndPass` labeled alternative
	 * in {@see GrammarParser::cond()}.
	 * @param $context The parse tree.
	 */
	public function exitCondAndPass(Context\CondAndPassContext $context): void;
	/**
	 * Enter a parse tree produced by the `CondAndExpr`
	 * labeled alternative in {@see GrammarParser::condAnd()}.
	 * @param $context The parse tree.
	 */
	public function enterCondAndExpr(Context\CondAndExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `CondAndExpr` labeled alternative
	 * in {@see GrammarParser::condAnd()}.
	 * @param $context The parse tree.
	 */
	public function exitCondAndExpr(Context\CondAndExprContext $context): void;
	/**
	 * Enter a parse tree produced by the `CondRelPass`
	 * labeled alternative in {@see GrammarParser::condAnd()}.
	 * @param $context The parse tree.
	 */
	public function enterCondRelPass(Context\CondRelPassContext $context): void;
	/**
	 * Exit a parse tree produced by the `CondRelPass` labeled alternative
	 * in {@see GrammarParser::condAnd()}.
	 * @param $context The parse tree.
	 */
	public function exitCondRelPass(Context\CondRelPassContext $context): void;
	/**
	 * Enter a parse tree produced by the `CondRelExpr`
	 * labeled alternative in {@see GrammarParser::condRel()}.
	 * @param $context The parse tree.
	 */
	public function enterCondRelExpr(Context\CondRelExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `CondRelExpr` labeled alternative
	 * in {@see GrammarParser::condRel()}.
	 * @param $context The parse tree.
	 */
	public function exitCondRelExpr(Context\CondRelExprContext $context): void;
	/**
	 * Enter a parse tree produced by the `CondGroupExpr`
	 * labeled alternative in {@see GrammarParser::condRel()}.
	 * @param $context The parse tree.
	 */
	public function enterCondGroupExpr(Context\CondGroupExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `CondGroupExpr` labeled alternative
	 * in {@see GrammarParser::condRel()}.
	 * @param $context The parse tree.
	 */
	public function exitCondGroupExpr(Context\CondGroupExprContext $context): void;
	/**
	 * Enter a parse tree produced by the `TermExpr`
	 * labeled alternative in {@see GrammarParser::e()}.
	 * @param $context The parse tree.
	 */
	public function enterTermExpr(Context\TermExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `TermExpr` labeled alternative
	 * in {@see GrammarParser::e()}.
	 * @param $context The parse tree.
	 */
	public function exitTermExpr(Context\TermExprContext $context): void;
	/**
	 * Enter a parse tree produced by the `AddExpr`
	 * labeled alternative in {@see GrammarParser::e()}.
	 * @param $context The parse tree.
	 */
	public function enterAddExpr(Context\AddExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `AddExpr` labeled alternative
	 * in {@see GrammarParser::e()}.
	 * @param $context The parse tree.
	 */
	public function exitAddExpr(Context\AddExprContext $context): void;
	/**
	 * Enter a parse tree produced by the `MulExpr`
	 * labeled alternative in {@see GrammarParser::term()}.
	 * @param $context The parse tree.
	 */
	public function enterMulExpr(Context\MulExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `MulExpr` labeled alternative
	 * in {@see GrammarParser::term()}.
	 * @param $context The parse tree.
	 */
	public function exitMulExpr(Context\MulExprContext $context): void;
	/**
	 * Enter a parse tree produced by the `FactorExpr`
	 * labeled alternative in {@see GrammarParser::term()}.
	 * @param $context The parse tree.
	 */
	public function enterFactorExpr(Context\FactorExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `FactorExpr` labeled alternative
	 * in {@see GrammarParser::term()}.
	 * @param $context The parse tree.
	 */
	public function exitFactorExpr(Context\FactorExprContext $context): void;
	/**
	 * Enter a parse tree produced by the `GroupExpr`
	 * labeled alternative in {@see GrammarParser::factor()}.
	 * @param $context The parse tree.
	 */
	public function enterGroupExpr(Context\GroupExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `GroupExpr` labeled alternative
	 * in {@see GrammarParser::factor()}.
	 * @param $context The parse tree.
	 */
	public function exitGroupExpr(Context\GroupExprContext $context): void;
	/**
	 * Enter a parse tree produced by the `IntExpr`
	 * labeled alternative in {@see GrammarParser::factor()}.
	 * @param $context The parse tree.
	 */
	public function enterIntExpr(Context\IntExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `IntExpr` labeled alternative
	 * in {@see GrammarParser::factor()}.
	 * @param $context The parse tree.
	 */
	public function exitIntExpr(Context\IntExprContext $context): void;
	/**
	 * Enter a parse tree produced by the `IdExpr`
	 * labeled alternative in {@see GrammarParser::factor()}.
	 * @param $context The parse tree.
	 */
	public function enterIdExpr(Context\IdExprContext $context): void;
	/**
	 * Exit a parse tree produced by the `IdExpr` labeled alternative
	 * in {@see GrammarParser::factor()}.
	 * @param $context The parse tree.
	 */
	public function exitIdExpr(Context\IdExprContext $context): void;
}