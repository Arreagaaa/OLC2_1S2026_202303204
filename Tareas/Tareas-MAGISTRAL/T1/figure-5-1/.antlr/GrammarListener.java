// Generated from /home/javier/Documentos/Proyectos_USAC/COMPI2/MAGIS/Tareas/T1/figure-5-1/Grammar.g4 by ANTLR 4.13.1
import org.antlr.v4.runtime.tree.ParseTreeListener;

/**
 * This interface defines a complete listener for a parse tree produced by
 * {@link GrammarParser}.
 */
public interface GrammarListener extends ParseTreeListener {
	/**
	 * Enter a parse tree produced by {@link GrammarParser#l}.
	 * @param ctx the parse tree
	 */
	void enterL(GrammarParser.LContext ctx);
	/**
	 * Exit a parse tree produced by {@link GrammarParser#l}.
	 * @param ctx the parse tree
	 */
	void exitL(GrammarParser.LContext ctx);
	/**
	 * Enter a parse tree produced by the {@code Add}
	 * labeled alternative in {@link GrammarParser#e}.
	 * @param ctx the parse tree
	 */
	void enterAdd(GrammarParser.AddContext ctx);
	/**
	 * Exit a parse tree produced by the {@code Add}
	 * labeled alternative in {@link GrammarParser#e}.
	 * @param ctx the parse tree
	 */
	void exitAdd(GrammarParser.AddContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ToTerm}
	 * labeled alternative in {@link GrammarParser#e}.
	 * @param ctx the parse tree
	 */
	void enterToTerm(GrammarParser.ToTermContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ToTerm}
	 * labeled alternative in {@link GrammarParser#e}.
	 * @param ctx the parse tree
	 */
	void exitToTerm(GrammarParser.ToTermContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ToFactor}
	 * labeled alternative in {@link GrammarParser#t}.
	 * @param ctx the parse tree
	 */
	void enterToFactor(GrammarParser.ToFactorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ToFactor}
	 * labeled alternative in {@link GrammarParser#t}.
	 * @param ctx the parse tree
	 */
	void exitToFactor(GrammarParser.ToFactorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code Mul}
	 * labeled alternative in {@link GrammarParser#t}.
	 * @param ctx the parse tree
	 */
	void enterMul(GrammarParser.MulContext ctx);
	/**
	 * Exit a parse tree produced by the {@code Mul}
	 * labeled alternative in {@link GrammarParser#t}.
	 * @param ctx the parse tree
	 */
	void exitMul(GrammarParser.MulContext ctx);
	/**
	 * Enter a parse tree produced by the {@code Par}
	 * labeled alternative in {@link GrammarParser#f}.
	 * @param ctx the parse tree
	 */
	void enterPar(GrammarParser.ParContext ctx);
	/**
	 * Exit a parse tree produced by the {@code Par}
	 * labeled alternative in {@link GrammarParser#f}.
	 * @param ctx the parse tree
	 */
	void exitPar(GrammarParser.ParContext ctx);
	/**
	 * Enter a parse tree produced by the {@code Int}
	 * labeled alternative in {@link GrammarParser#f}.
	 * @param ctx the parse tree
	 */
	void enterInt(GrammarParser.IntContext ctx);
	/**
	 * Exit a parse tree produced by the {@code Int}
	 * labeled alternative in {@link GrammarParser#f}.
	 * @param ctx the parse tree
	 */
	void exitInt(GrammarParser.IntContext ctx);
}