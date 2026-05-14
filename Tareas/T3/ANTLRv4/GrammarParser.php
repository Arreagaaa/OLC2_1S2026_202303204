<?php

/*
 * Generated from Grammar.g4 by ANTLR 4.13.2
 */

namespace {
	use Antlr\Antlr4\Runtime\Atn\ATN;
	use Antlr\Antlr4\Runtime\Atn\ATNDeserializer;
	use Antlr\Antlr4\Runtime\Atn\ParserATNSimulator;
	use Antlr\Antlr4\Runtime\Dfa\DFA;
	use Antlr\Antlr4\Runtime\Error\Exceptions\FailedPredicateException;
	use Antlr\Antlr4\Runtime\Error\Exceptions\NoViableAltException;
	use Antlr\Antlr4\Runtime\PredictionContexts\PredictionContextCache;
	use Antlr\Antlr4\Runtime\Error\Exceptions\RecognitionException;
	use Antlr\Antlr4\Runtime\RuleContext;
	use Antlr\Antlr4\Runtime\Token;
	use Antlr\Antlr4\Runtime\TokenStream;
	use Antlr\Antlr4\Runtime\Vocabulary;
	use Antlr\Antlr4\Runtime\VocabularyImpl;
	use Antlr\Antlr4\Runtime\RuntimeMetaData;
	use Antlr\Antlr4\Runtime\Parser;

	final class GrammarParser extends Parser
	{
		public const T__0 = 1, T__1 = 2, T__2 = 3, T__3 = 4, T__4 = 5, T__5 = 6, 
               T__6 = 7, T__7 = 8, T__8 = 9, T__9 = 10, T__10 = 11, T__11 = 12, 
               T__12 = 13, T__13 = 14, T__14 = 15, T__15 = 16, T__16 = 17, 
               T__17 = 18, T__18 = 19, T__19 = 20, INT = 21, ID = 22, STRING = 23, 
               WS = 24;

		public const RULE_p = 0, RULE_stmt = 1, RULE_block = 2, RULE_cond = 3, 
               RULE_condAnd = 4, RULE_condRel = 5, RULE_e = 6, RULE_term = 7, 
               RULE_factor = 8;

		/**
		 * @var array<string>
		 */
		public const RULE_NAMES = [
			'p', 'stmt', 'block', 'cond', 'condAnd', 'condRel', 'e', 'term', 'factor'
		];

		/**
		 * @var array<string|null>
		 */
		private const LITERAL_NAMES = [
		    null, "':='", "'print'", "'('", "')'", "'if'", "'else'", "'{'", "'}'", 
		    "'||'", "'&&'", "'=='", "'!='", "'<'", "'>'", "'<='", "'>='", "'+'", 
		    "'-'", "'*'", "'/'"
		];

		/**
		 * @var array<string>
		 */
		private const SYMBOLIC_NAMES = [
		    null, null, null, null, null, null, null, null, null, null, null, 
		    null, null, null, null, null, null, null, null, null, null, "INT", 
		    "ID", "STRING", "WS"
		];

		private const SERIALIZED_ATN =
			[4, 1, 24, 118, 2, 0, 7, 0, 2, 1, 7, 1, 2, 2, 7, 2, 2, 3, 7, 3, 2, 4, 
		    7, 4, 2, 5, 7, 5, 2, 6, 7, 6, 2, 7, 7, 7, 2, 8, 7, 8, 1, 0, 5, 0, 
		    20, 8, 0, 10, 0, 12, 0, 23, 9, 0, 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 
		    1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 
		    1, 1, 1, 3, 1, 43, 8, 1, 3, 1, 45, 8, 1, 1, 2, 1, 2, 5, 2, 49, 8, 
		    2, 10, 2, 12, 2, 52, 9, 2, 1, 2, 1, 2, 1, 3, 1, 3, 1, 3, 1, 3, 1, 
		    3, 1, 3, 5, 3, 62, 8, 3, 10, 3, 12, 3, 65, 9, 3, 1, 4, 1, 4, 1, 4, 
		    1, 4, 1, 4, 1, 4, 5, 4, 73, 8, 4, 10, 4, 12, 4, 76, 9, 4, 1, 5, 1, 
		    5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 3, 5, 86, 8, 5, 1, 6, 1, 6, 
		    1, 6, 1, 6, 1, 6, 1, 6, 5, 6, 94, 8, 6, 10, 6, 12, 6, 97, 9, 6, 1, 
		    7, 1, 7, 1, 7, 1, 7, 1, 7, 1, 7, 5, 7, 105, 8, 7, 10, 7, 12, 7, 108, 
		    9, 7, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 3, 8, 116, 8, 8, 1, 8, 0, 
		    4, 6, 8, 12, 14, 9, 0, 2, 4, 6, 8, 10, 12, 14, 16, 0, 3, 1, 0, 11, 
		    16, 1, 0, 17, 18, 1, 0, 19, 20, 121, 0, 21, 1, 0, 0, 0, 2, 44, 1, 
		    0, 0, 0, 4, 46, 1, 0, 0, 0, 6, 55, 1, 0, 0, 0, 8, 66, 1, 0, 0, 0, 
		    10, 85, 1, 0, 0, 0, 12, 87, 1, 0, 0, 0, 14, 98, 1, 0, 0, 0, 16, 115, 
		    1, 0, 0, 0, 18, 20, 3, 2, 1, 0, 19, 18, 1, 0, 0, 0, 20, 23, 1, 0, 
		    0, 0, 21, 19, 1, 0, 0, 0, 21, 22, 1, 0, 0, 0, 22, 24, 1, 0, 0, 0, 
		    23, 21, 1, 0, 0, 0, 24, 25, 5, 0, 0, 1, 25, 1, 1, 0, 0, 0, 26, 27, 
		    5, 22, 0, 0, 27, 28, 5, 1, 0, 0, 28, 45, 3, 12, 6, 0, 29, 30, 5, 2, 
		    0, 0, 30, 31, 5, 3, 0, 0, 31, 32, 5, 23, 0, 0, 32, 45, 5, 4, 0, 0, 
		    33, 34, 5, 2, 0, 0, 34, 35, 5, 3, 0, 0, 35, 36, 5, 22, 0, 0, 36, 45, 
		    5, 4, 0, 0, 37, 38, 5, 5, 0, 0, 38, 39, 3, 6, 3, 0, 39, 42, 3, 4, 
		    2, 0, 40, 41, 5, 6, 0, 0, 41, 43, 3, 4, 2, 0, 42, 40, 1, 0, 0, 0, 
		    42, 43, 1, 0, 0, 0, 43, 45, 1, 0, 0, 0, 44, 26, 1, 0, 0, 0, 44, 29, 
		    1, 0, 0, 0, 44, 33, 1, 0, 0, 0, 44, 37, 1, 0, 0, 0, 45, 3, 1, 0, 0, 
		    0, 46, 50, 5, 7, 0, 0, 47, 49, 3, 2, 1, 0, 48, 47, 1, 0, 0, 0, 49, 
		    52, 1, 0, 0, 0, 50, 48, 1, 0, 0, 0, 50, 51, 1, 0, 0, 0, 51, 53, 1, 
		    0, 0, 0, 52, 50, 1, 0, 0, 0, 53, 54, 5, 8, 0, 0, 54, 5, 1, 0, 0, 0, 
		    55, 56, 6, 3, -1, 0, 56, 57, 3, 8, 4, 0, 57, 63, 1, 0, 0, 0, 58, 59, 
		    10, 2, 0, 0, 59, 60, 5, 9, 0, 0, 60, 62, 3, 8, 4, 0, 61, 58, 1, 0, 
		    0, 0, 62, 65, 1, 0, 0, 0, 63, 61, 1, 0, 0, 0, 63, 64, 1, 0, 0, 0, 
		    64, 7, 1, 0, 0, 0, 65, 63, 1, 0, 0, 0, 66, 67, 6, 4, -1, 0, 67, 68, 
		    3, 10, 5, 0, 68, 74, 1, 0, 0, 0, 69, 70, 10, 2, 0, 0, 70, 71, 5, 10, 
		    0, 0, 71, 73, 3, 10, 5, 0, 72, 69, 1, 0, 0, 0, 73, 76, 1, 0, 0, 0, 
		    74, 72, 1, 0, 0, 0, 74, 75, 1, 0, 0, 0, 75, 9, 1, 0, 0, 0, 76, 74, 
		    1, 0, 0, 0, 77, 78, 3, 12, 6, 0, 78, 79, 7, 0, 0, 0, 79, 80, 3, 12, 
		    6, 0, 80, 86, 1, 0, 0, 0, 81, 82, 5, 3, 0, 0, 82, 83, 3, 6, 3, 0, 
		    83, 84, 5, 4, 0, 0, 84, 86, 1, 0, 0, 0, 85, 77, 1, 0, 0, 0, 85, 81, 
		    1, 0, 0, 0, 86, 11, 1, 0, 0, 0, 87, 88, 6, 6, -1, 0, 88, 89, 3, 14, 
		    7, 0, 89, 95, 1, 0, 0, 0, 90, 91, 10, 2, 0, 0, 91, 92, 7, 1, 0, 0, 
		    92, 94, 3, 14, 7, 0, 93, 90, 1, 0, 0, 0, 94, 97, 1, 0, 0, 0, 95, 93, 
		    1, 0, 0, 0, 95, 96, 1, 0, 0, 0, 96, 13, 1, 0, 0, 0, 97, 95, 1, 0, 
		    0, 0, 98, 99, 6, 7, -1, 0, 99, 100, 3, 16, 8, 0, 100, 106, 1, 0, 0, 
		    0, 101, 102, 10, 2, 0, 0, 102, 103, 7, 2, 0, 0, 103, 105, 3, 16, 8, 
		    0, 104, 101, 1, 0, 0, 0, 105, 108, 1, 0, 0, 0, 106, 104, 1, 0, 0, 
		    0, 106, 107, 1, 0, 0, 0, 107, 15, 1, 0, 0, 0, 108, 106, 1, 0, 0, 0, 
		    109, 110, 5, 3, 0, 0, 110, 111, 3, 12, 6, 0, 111, 112, 5, 4, 0, 0, 
		    112, 116, 1, 0, 0, 0, 113, 116, 5, 21, 0, 0, 114, 116, 5, 22, 0, 0, 
		    115, 109, 1, 0, 0, 0, 115, 113, 1, 0, 0, 0, 115, 114, 1, 0, 0, 0, 
		    116, 17, 1, 0, 0, 0, 10, 21, 42, 44, 50, 63, 74, 85, 95, 106, 115];
		protected static $atn;
		protected static $decisionToDFA;
		protected static $sharedContextCache;

		public function __construct(TokenStream $input)
		{
			parent::__construct($input);

			self::initialize();

			$this->interp = new ParserATNSimulator($this, self::$atn, self::$decisionToDFA, self::$sharedContextCache);
		}

		private static function initialize(): void
		{
			if (self::$atn !== null) {
				return;
			}

			RuntimeMetaData::checkVersion('4.13.2', RuntimeMetaData::VERSION);

			$atn = (new ATNDeserializer())->deserialize(self::SERIALIZED_ATN);

			$decisionToDFA = [];
			for ($i = 0, $count = $atn->getNumberOfDecisions(); $i < $count; $i++) {
				$decisionToDFA[] = new DFA($atn->getDecisionState($i), $i);
			}

			self::$atn = $atn;
			self::$decisionToDFA = $decisionToDFA;
			self::$sharedContextCache = new PredictionContextCache();
		}

		public function getGrammarFileName(): string
		{
			return "Grammar.g4";
		}

		public function getRuleNames(): array
		{
			return self::RULE_NAMES;
		}

		public function getSerializedATN(): array
		{
			return self::SERIALIZED_ATN;
		}

		public function getATN(): ATN
		{
			return self::$atn;
		}

		public function getVocabulary(): Vocabulary
        {
            static $vocabulary;

			return $vocabulary = $vocabulary ?? new VocabularyImpl(self::LITERAL_NAMES, self::SYMBOLIC_NAMES);
        }

		/**
		 * @throws RecognitionException
		 */
		public function p(): Context\PContext
		{
		    $localContext = new Context\PContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 0, self::RULE_p);

		    try {
		        $localContext = new Context\ProgramContext($localContext);
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(21);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 4194340) !== 0)) {
		        	$this->setState(18);
		        	$this->stmt();
		        	$this->setState(23);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(24);
		        $this->match(self::EOF);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function stmt(): Context\StmtContext
		{
		    $localContext = new Context\StmtContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 2, self::RULE_stmt);

		    try {
		        $this->setState(44);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 2, $this->ctx)) {
		        	case 1:
		        	    $localContext = new Context\AssignStatementContext($localContext);
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(26);
		        	    $this->match(self::ID);
		        	    $this->setState(27);
		        	    $this->match(self::T__0);
		        	    $this->setState(28);
		        	    $this->recursiveE(0);
		        	break;

		        	case 2:
		        	    $localContext = new Context\PrintStringStatementContext($localContext);
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(29);
		        	    $this->match(self::T__1);
		        	    $this->setState(30);
		        	    $this->match(self::T__2);
		        	    $this->setState(31);
		        	    $this->match(self::STRING);
		        	    $this->setState(32);
		        	    $this->match(self::T__3);
		        	break;

		        	case 3:
		        	    $localContext = new Context\PrintStatementContext($localContext);
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(33);
		        	    $this->match(self::T__1);
		        	    $this->setState(34);
		        	    $this->match(self::T__2);
		        	    $this->setState(35);
		        	    $this->match(self::ID);
		        	    $this->setState(36);
		        	    $this->match(self::T__3);
		        	break;

		        	case 4:
		        	    $localContext = new Context\IfStatementContext($localContext);
		        	    $this->enterOuterAlt($localContext, 4);
		        	    $this->setState(37);
		        	    $this->match(self::T__4);
		        	    $this->setState(38);
		        	    $this->recursiveCond(0);
		        	    $this->setState(39);
		        	    $this->block();
		        	    $this->setState(42);
		        	    $this->errorHandler->sync($this);
		        	    $_la = $this->input->LA(1);

		        	    if ($_la === self::T__5) {
		        	    	$this->setState(40);
		        	    	$this->match(self::T__5);
		        	    	$this->setState(41);
		        	    	$this->block();
		        	    }
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function block(): Context\BlockContext
		{
		    $localContext = new Context\BlockContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 4, self::RULE_block);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(46);
		        $this->match(self::T__6);
		        $this->setState(50);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 4194340) !== 0)) {
		        	$this->setState(47);
		        	$this->stmt();
		        	$this->setState(52);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(53);
		        $this->match(self::T__7);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function cond(): Context\CondContext
		{
			return $this->recursiveCond(0);
		}

		/**
		 * @throws RecognitionException
		 */
		private function recursiveCond(int $precedence): Context\CondContext
		{
			$parentContext = $this->ctx;
			$parentState = $this->getState();
			$localContext = new Context\CondContext($this->ctx, $parentState);
			$previousContext = $localContext;
			$startState = 6;
			$this->enterRecursionRule($localContext, 6, self::RULE_cond, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$localContext = new Context\CondAndPassContext($localContext);
				$this->ctx = $localContext;
				$previousContext = $localContext;

				$this->setState(56);
				$this->recursiveCondAnd(0);
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(63);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 4, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$localContext = new Context\CondOrExprContext(new Context\CondContext($parentContext, $parentState));
						$this->pushNewRecursionContext($localContext, $startState, self::RULE_cond);
						$this->setState(58);

						if (!($this->precpred($this->ctx, 2))) {
						    throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 2)");
						}
						$this->setState(59);
						$this->match(self::T__8);
						$this->setState(60);
						$this->recursiveCondAnd(0); 
					}

					$this->setState(65);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 4, $this->ctx);
				}
			} catch (RecognitionException $exception) {
				$localContext->exception = $exception;
				$this->errorHandler->reportError($this, $exception);
				$this->errorHandler->recover($this, $exception);
			} finally {
				$this->unrollRecursionContexts($parentContext);
			}

			return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function condAnd(): Context\CondAndContext
		{
			return $this->recursiveCondAnd(0);
		}

		/**
		 * @throws RecognitionException
		 */
		private function recursiveCondAnd(int $precedence): Context\CondAndContext
		{
			$parentContext = $this->ctx;
			$parentState = $this->getState();
			$localContext = new Context\CondAndContext($this->ctx, $parentState);
			$previousContext = $localContext;
			$startState = 8;
			$this->enterRecursionRule($localContext, 8, self::RULE_condAnd, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$localContext = new Context\CondRelPassContext($localContext);
				$this->ctx = $localContext;
				$previousContext = $localContext;

				$this->setState(67);
				$this->condRel();
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(74);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 5, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$localContext = new Context\CondAndExprContext(new Context\CondAndContext($parentContext, $parentState));
						$this->pushNewRecursionContext($localContext, $startState, self::RULE_condAnd);
						$this->setState(69);

						if (!($this->precpred($this->ctx, 2))) {
						    throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 2)");
						}
						$this->setState(70);
						$this->match(self::T__9);
						$this->setState(71);
						$this->condRel(); 
					}

					$this->setState(76);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 5, $this->ctx);
				}
			} catch (RecognitionException $exception) {
				$localContext->exception = $exception;
				$this->errorHandler->reportError($this, $exception);
				$this->errorHandler->recover($this, $exception);
			} finally {
				$this->unrollRecursionContexts($parentContext);
			}

			return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function condRel(): Context\CondRelContext
		{
		    $localContext = new Context\CondRelContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 10, self::RULE_condRel);

		    try {
		        $this->setState(85);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 6, $this->ctx)) {
		        	case 1:
		        	    $localContext = new Context\CondRelExprContext($localContext);
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(77);
		        	    $this->recursiveE(0);
		        	    $this->setState(78);

		        	    $localContext->op = $this->input->LT(1);
		        	    $_la = $this->input->LA(1);

		        	    if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 129024) !== 0))) {
		        	    	    $localContext->op = $this->errorHandler->recoverInline($this);
		        	    } else {
		        	    	if ($this->input->LA(1) === Token::EOF) {
		        	    	    $this->matchedEOF = true;
		        	        }

		        	    	$this->errorHandler->reportMatch($this);
		        	    	$this->consume();
		        	    }
		        	    $this->setState(79);
		        	    $this->recursiveE(0);
		        	break;

		        	case 2:
		        	    $localContext = new Context\CondGroupExprContext($localContext);
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(81);
		        	    $this->match(self::T__2);
		        	    $this->setState(82);
		        	    $this->recursiveCond(0);
		        	    $this->setState(83);
		        	    $this->match(self::T__3);
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function e(): Context\EContext
		{
			return $this->recursiveE(0);
		}

		/**
		 * @throws RecognitionException
		 */
		private function recursiveE(int $precedence): Context\EContext
		{
			$parentContext = $this->ctx;
			$parentState = $this->getState();
			$localContext = new Context\EContext($this->ctx, $parentState);
			$previousContext = $localContext;
			$startState = 12;
			$this->enterRecursionRule($localContext, 12, self::RULE_e, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$localContext = new Context\TermExprContext($localContext);
				$this->ctx = $localContext;
				$previousContext = $localContext;

				$this->setState(88);
				$this->recursiveTerm(0);
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(95);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 7, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$localContext = new Context\AddExprContext(new Context\EContext($parentContext, $parentState));
						$this->pushNewRecursionContext($localContext, $startState, self::RULE_e);
						$this->setState(90);

						if (!($this->precpred($this->ctx, 2))) {
						    throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 2)");
						}
						$this->setState(91);

						$localContext->op = $this->input->LT(1);
						$_la = $this->input->LA(1);

						if (!($_la === self::T__16 || $_la === self::T__17)) {
							    $localContext->op = $this->errorHandler->recoverInline($this);
						} else {
							if ($this->input->LA(1) === Token::EOF) {
							    $this->matchedEOF = true;
						    }

							$this->errorHandler->reportMatch($this);
							$this->consume();
						}
						$this->setState(92);
						$this->recursiveTerm(0); 
					}

					$this->setState(97);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 7, $this->ctx);
				}
			} catch (RecognitionException $exception) {
				$localContext->exception = $exception;
				$this->errorHandler->reportError($this, $exception);
				$this->errorHandler->recover($this, $exception);
			} finally {
				$this->unrollRecursionContexts($parentContext);
			}

			return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function term(): Context\TermContext
		{
			return $this->recursiveTerm(0);
		}

		/**
		 * @throws RecognitionException
		 */
		private function recursiveTerm(int $precedence): Context\TermContext
		{
			$parentContext = $this->ctx;
			$parentState = $this->getState();
			$localContext = new Context\TermContext($this->ctx, $parentState);
			$previousContext = $localContext;
			$startState = 14;
			$this->enterRecursionRule($localContext, 14, self::RULE_term, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$localContext = new Context\FactorExprContext($localContext);
				$this->ctx = $localContext;
				$previousContext = $localContext;

				$this->setState(99);
				$this->factor();
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(106);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 8, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$localContext = new Context\MulExprContext(new Context\TermContext($parentContext, $parentState));
						$this->pushNewRecursionContext($localContext, $startState, self::RULE_term);
						$this->setState(101);

						if (!($this->precpred($this->ctx, 2))) {
						    throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 2)");
						}
						$this->setState(102);

						$localContext->op = $this->input->LT(1);
						$_la = $this->input->LA(1);

						if (!($_la === self::T__18 || $_la === self::T__19)) {
							    $localContext->op = $this->errorHandler->recoverInline($this);
						} else {
							if ($this->input->LA(1) === Token::EOF) {
							    $this->matchedEOF = true;
						    }

							$this->errorHandler->reportMatch($this);
							$this->consume();
						}
						$this->setState(103);
						$this->factor(); 
					}

					$this->setState(108);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 8, $this->ctx);
				}
			} catch (RecognitionException $exception) {
				$localContext->exception = $exception;
				$this->errorHandler->reportError($this, $exception);
				$this->errorHandler->recover($this, $exception);
			} finally {
				$this->unrollRecursionContexts($parentContext);
			}

			return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function factor(): Context\FactorContext
		{
		    $localContext = new Context\FactorContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 16, self::RULE_factor);

		    try {
		        $this->setState(115);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::T__2:
		            	$localContext = new Context\GroupExprContext($localContext);
		            	$this->enterOuterAlt($localContext, 1);
		            	$this->setState(109);
		            	$this->match(self::T__2);
		            	$this->setState(110);
		            	$this->recursiveE(0);
		            	$this->setState(111);
		            	$this->match(self::T__3);
		            	break;

		            case self::INT:
		            	$localContext = new Context\IntExprContext($localContext);
		            	$this->enterOuterAlt($localContext, 2);
		            	$this->setState(113);
		            	$this->match(self::INT);
		            	break;

		            case self::ID:
		            	$localContext = new Context\IdExprContext($localContext);
		            	$this->enterOuterAlt($localContext, 3);
		            	$this->setState(114);
		            	$this->match(self::ID);
		            	break;

		        default:
		        	throw new NoViableAltException($this);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		public function sempred(?RuleContext $localContext, int $ruleIndex, int $predicateIndex): bool
		{
			switch ($ruleIndex) {
					case 3:
						return $this->sempredCond($localContext, $predicateIndex);

					case 4:
						return $this->sempredCondAnd($localContext, $predicateIndex);

					case 6:
						return $this->sempredE($localContext, $predicateIndex);

					case 7:
						return $this->sempredTerm($localContext, $predicateIndex);

				default:
					return true;
				}
		}

		private function sempredCond(?Context\CondContext $localContext, int $predicateIndex): bool
		{
			switch ($predicateIndex) {
			    case 0:
			        return $this->precpred($this->ctx, 2);
			}

			return true;
		}

		private function sempredCondAnd(?Context\CondAndContext $localContext, int $predicateIndex): bool
		{
			switch ($predicateIndex) {
			    case 1:
			        return $this->precpred($this->ctx, 2);
			}

			return true;
		}

		private function sempredE(?Context\EContext $localContext, int $predicateIndex): bool
		{
			switch ($predicateIndex) {
			    case 2:
			        return $this->precpred($this->ctx, 2);
			}

			return true;
		}

		private function sempredTerm(?Context\TermContext $localContext, int $predicateIndex): bool
		{
			switch ($predicateIndex) {
			    case 3:
			        return $this->precpred($this->ctx, 2);
			}

			return true;
		}
	}
}

namespace Context {
	use Antlr\Antlr4\Runtime\ParserRuleContext;
	use Antlr\Antlr4\Runtime\Token;
	use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;
	use Antlr\Antlr4\Runtime\Tree\TerminalNode;
	use Antlr\Antlr4\Runtime\Tree\ParseTreeListener;
	use GrammarParser;
	use GrammarVisitor;
	use GrammarListener;

	class PContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GrammarParser::RULE_p;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class ProgramContext extends PContext
	{
		public function __construct(PContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function EOF(): ?TerminalNode
	    {
	        return $this->getToken(GrammarParser::EOF, 0);
	    }

	    /**
	     * @return array<StmtContext>|StmtContext|null
	     */
	    public function stmt(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(StmtContext::class);
	    	}

	        return $this->getTypedRuleContext(StmtContext::class, $index);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterProgram($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitProgram($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitProgram($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class StmtContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GrammarParser::RULE_stmt;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class PrintStringStatementContext extends StmtContext
	{
		public function __construct(StmtContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function STRING(): ?TerminalNode
	    {
	        return $this->getToken(GrammarParser::STRING, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterPrintStringStatement($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitPrintStringStatement($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitPrintStringStatement($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class IfStatementContext extends StmtContext
	{
		public function __construct(StmtContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function cond(): ?CondContext
	    {
	    	return $this->getTypedRuleContext(CondContext::class, 0);
	    }

	    /**
	     * @return array<BlockContext>|BlockContext|null
	     */
	    public function block(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(BlockContext::class);
	    	}

	        return $this->getTypedRuleContext(BlockContext::class, $index);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterIfStatement($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitIfStatement($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitIfStatement($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class PrintStatementContext extends StmtContext
	{
		public function __construct(StmtContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function ID(): ?TerminalNode
	    {
	        return $this->getToken(GrammarParser::ID, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterPrintStatement($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitPrintStatement($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitPrintStatement($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class AssignStatementContext extends StmtContext
	{
		public function __construct(StmtContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function ID(): ?TerminalNode
	    {
	        return $this->getToken(GrammarParser::ID, 0);
	    }

	    public function e(): ?EContext
	    {
	    	return $this->getTypedRuleContext(EContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterAssignStatement($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitAssignStatement($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitAssignStatement($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class BlockContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GrammarParser::RULE_block;
	    }

	    /**
	     * @return array<StmtContext>|StmtContext|null
	     */
	    public function stmt(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(StmtContext::class);
	    	}

	        return $this->getTypedRuleContext(StmtContext::class, $index);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterBlock($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitBlock($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitBlock($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class CondContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GrammarParser::RULE_cond;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class CondOrExprContext extends CondContext
	{
		public function __construct(CondContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function cond(): ?CondContext
	    {
	    	return $this->getTypedRuleContext(CondContext::class, 0);
	    }

	    public function condAnd(): ?CondAndContext
	    {
	    	return $this->getTypedRuleContext(CondAndContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterCondOrExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitCondOrExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitCondOrExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class CondAndPassContext extends CondContext
	{
		public function __construct(CondContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function condAnd(): ?CondAndContext
	    {
	    	return $this->getTypedRuleContext(CondAndContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterCondAndPass($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitCondAndPass($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitCondAndPass($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class CondAndContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GrammarParser::RULE_condAnd;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class CondAndExprContext extends CondAndContext
	{
		public function __construct(CondAndContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function condAnd(): ?CondAndContext
	    {
	    	return $this->getTypedRuleContext(CondAndContext::class, 0);
	    }

	    public function condRel(): ?CondRelContext
	    {
	    	return $this->getTypedRuleContext(CondRelContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterCondAndExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitCondAndExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitCondAndExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class CondRelPassContext extends CondAndContext
	{
		public function __construct(CondAndContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function condRel(): ?CondRelContext
	    {
	    	return $this->getTypedRuleContext(CondRelContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterCondRelPass($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitCondRelPass($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitCondRelPass($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class CondRelContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GrammarParser::RULE_condRel;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class CondRelExprContext extends CondRelContext
	{
		/**
		 * @var Token|null $op
		 */
		public $op;

		public function __construct(CondRelContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    /**
	     * @return array<EContext>|EContext|null
	     */
	    public function e(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(EContext::class);
	    	}

	        return $this->getTypedRuleContext(EContext::class, $index);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterCondRelExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitCondRelExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitCondRelExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class CondGroupExprContext extends CondRelContext
	{
		public function __construct(CondRelContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function cond(): ?CondContext
	    {
	    	return $this->getTypedRuleContext(CondContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterCondGroupExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitCondGroupExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitCondGroupExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class EContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GrammarParser::RULE_e;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class TermExprContext extends EContext
	{
		public function __construct(EContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function term(): ?TermContext
	    {
	    	return $this->getTypedRuleContext(TermContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterTermExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitTermExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitTermExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class AddExprContext extends EContext
	{
		/**
		 * @var Token|null $op
		 */
		public $op;

		public function __construct(EContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function e(): ?EContext
	    {
	    	return $this->getTypedRuleContext(EContext::class, 0);
	    }

	    public function term(): ?TermContext
	    {
	    	return $this->getTypedRuleContext(TermContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterAddExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitAddExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitAddExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class TermContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GrammarParser::RULE_term;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class MulExprContext extends TermContext
	{
		/**
		 * @var Token|null $op
		 */
		public $op;

		public function __construct(TermContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function term(): ?TermContext
	    {
	    	return $this->getTypedRuleContext(TermContext::class, 0);
	    }

	    public function factor(): ?FactorContext
	    {
	    	return $this->getTypedRuleContext(FactorContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterMulExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitMulExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitMulExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class FactorExprContext extends TermContext
	{
		public function __construct(TermContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function factor(): ?FactorContext
	    {
	    	return $this->getTypedRuleContext(FactorContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterFactorExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitFactorExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitFactorExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class FactorContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GrammarParser::RULE_factor;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class GroupExprContext extends FactorContext
	{
		public function __construct(FactorContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function e(): ?EContext
	    {
	    	return $this->getTypedRuleContext(EContext::class, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterGroupExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitGroupExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitGroupExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class IdExprContext extends FactorContext
	{
		public function __construct(FactorContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function ID(): ?TerminalNode
	    {
	        return $this->getToken(GrammarParser::ID, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterIdExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitIdExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitIdExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class IntExprContext extends FactorContext
	{
		public function __construct(FactorContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function INT(): ?TerminalNode
	    {
	        return $this->getToken(GrammarParser::INT, 0);
	    }

		public function enterRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->enterIntExpr($this);
		    }
		}

		public function exitRule(ParseTreeListener $listener): void
		{
			if ($listener instanceof GrammarListener) {
			    $listener->exitIntExpr($this);
		    }
		}

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GrammarVisitor) {
			    return $visitor->visitIntExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 
}