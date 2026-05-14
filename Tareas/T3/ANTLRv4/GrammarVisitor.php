<?php

/*
 * Generated from Grammar.g4 by ANTLR 4.13.2
 */

use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;

/**
 * This interface defines a complete generic visitor for a parse tree produced by {@see GrammarParser}.
 */
interface GrammarVisitor extends ParseTreeVisitor
{
	/**
	 * Visit a parse tree produced by the `Program` labeled alternative
	 * in {@see GrammarParser::p()}.
	 *
	 * @param Context\ProgramContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitProgram(Context\ProgramContext $context);

	/**
	 * Visit a parse tree produced by the `AssignStatement` labeled alternative
	 * in {@see GrammarParser::stmt()}.
	 *
	 * @param Context\AssignStatementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitAssignStatement(Context\AssignStatementContext $context);

	/**
	 * Visit a parse tree produced by the `PrintStringStatement` labeled alternative
	 * in {@see GrammarParser::stmt()}.
	 *
	 * @param Context\PrintStringStatementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitPrintStringStatement(Context\PrintStringStatementContext $context);

	/**
	 * Visit a parse tree produced by the `PrintStatement` labeled alternative
	 * in {@see GrammarParser::stmt()}.
	 *
	 * @param Context\PrintStatementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitPrintStatement(Context\PrintStatementContext $context);

	/**
	 * Visit a parse tree produced by the `IfStatement` labeled alternative
	 * in {@see GrammarParser::stmt()}.
	 *
	 * @param Context\IfStatementContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitIfStatement(Context\IfStatementContext $context);

	/**
	 * Visit a parse tree produced by {@see GrammarParser::block()}.
	 *
	 * @param Context\BlockContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitBlock(Context\BlockContext $context);

	/**
	 * Visit a parse tree produced by the `CondOrExpr` labeled alternative
	 * in {@see GrammarParser::cond()}.
	 *
	 * @param Context\CondOrExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitCondOrExpr(Context\CondOrExprContext $context);

	/**
	 * Visit a parse tree produced by the `CondAndPass` labeled alternative
	 * in {@see GrammarParser::cond()}.
	 *
	 * @param Context\CondAndPassContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitCondAndPass(Context\CondAndPassContext $context);

	/**
	 * Visit a parse tree produced by the `CondAndExpr` labeled alternative
	 * in {@see GrammarParser::condAnd()}.
	 *
	 * @param Context\CondAndExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitCondAndExpr(Context\CondAndExprContext $context);

	/**
	 * Visit a parse tree produced by the `CondRelPass` labeled alternative
	 * in {@see GrammarParser::condAnd()}.
	 *
	 * @param Context\CondRelPassContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitCondRelPass(Context\CondRelPassContext $context);

	/**
	 * Visit a parse tree produced by the `CondRelExpr` labeled alternative
	 * in {@see GrammarParser::condRel()}.
	 *
	 * @param Context\CondRelExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitCondRelExpr(Context\CondRelExprContext $context);

	/**
	 * Visit a parse tree produced by the `CondGroupExpr` labeled alternative
	 * in {@see GrammarParser::condRel()}.
	 *
	 * @param Context\CondGroupExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitCondGroupExpr(Context\CondGroupExprContext $context);

	/**
	 * Visit a parse tree produced by the `TermExpr` labeled alternative
	 * in {@see GrammarParser::e()}.
	 *
	 * @param Context\TermExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitTermExpr(Context\TermExprContext $context);

	/**
	 * Visit a parse tree produced by the `AddExpr` labeled alternative
	 * in {@see GrammarParser::e()}.
	 *
	 * @param Context\AddExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitAddExpr(Context\AddExprContext $context);

	/**
	 * Visit a parse tree produced by the `MulExpr` labeled alternative
	 * in {@see GrammarParser::term()}.
	 *
	 * @param Context\MulExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitMulExpr(Context\MulExprContext $context);

	/**
	 * Visit a parse tree produced by the `FactorExpr` labeled alternative
	 * in {@see GrammarParser::term()}.
	 *
	 * @param Context\FactorExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitFactorExpr(Context\FactorExprContext $context);

	/**
	 * Visit a parse tree produced by the `GroupExpr` labeled alternative
	 * in {@see GrammarParser::factor()}.
	 *
	 * @param Context\GroupExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitGroupExpr(Context\GroupExprContext $context);

	/**
	 * Visit a parse tree produced by the `IntExpr` labeled alternative
	 * in {@see GrammarParser::factor()}.
	 *
	 * @param Context\IntExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitIntExpr(Context\IntExprContext $context);

	/**
	 * Visit a parse tree produced by the `IdExpr` labeled alternative
	 * in {@see GrammarParser::factor()}.
	 *
	 * @param Context\IdExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitIdExpr(Context\IdExprContext $context);
}