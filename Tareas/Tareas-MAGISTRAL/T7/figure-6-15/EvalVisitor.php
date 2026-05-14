<?php

use Context\T_ruleContext;
use Context\B_intContext;
use Context\B_floatContext;
use Context\C_arrayContext;
use Context\C_emptyContext;

class EvalVisitor extends GrammarBaseVisitor
{
    private $inherited_type = "";
    private $inherited_width = 0;

    public function visitT_rule($ctx)
    {

        //Visita B para obtener sus atributos sintetizados
        $b_attrs = $this->visit($ctx->b());

        // t = B.Type; w =  B.width; (simulamos atributos heredados guardandolos)
        $this->inherited_type = $b_attrs['type'];
        $this->inherited_width = $b_attrs['width'];

        $c_attrs = $this->visit($ctx->c());

        echo "type = " . $c_attrs['type'] . "\nwidth = " . $c_attrs['width'] . "\n";

        return $c_attrs;
    }

    public function visitB_int($ctx)
    {
        return [
            'type' => 'interger',
            'width' => 4
        ];
    }

    public function visitB_float($ctx)
    {
        return [
            'type' => 'float',
            'width' => 8
        ];
    }

    public function visitC_array($ctx)
    {

        // '[' num ']' C1
        $num_val = (int) $ctx->NUMBER()->getText();

        // Los atributos heredados de C (C1 em este caso) son los mismos (t y w),
        // los cuales ya estan alamacenados en $this -> inherited_type y $this->inherited_width;

        $c1_attrs = $this->visit($ctx->c());

        return [
            'type' => "array(" . $num_val . ", " . $c1_attrs['type'] . ")",
            'width' => $num_val * $c1_attrs['width']
        ];
    }

    public function visitC_empty($ctx)
    {
        return [
            'type' => $this->inherited_type,
            'width' => $this->inherited_width
        ];
    }
}
