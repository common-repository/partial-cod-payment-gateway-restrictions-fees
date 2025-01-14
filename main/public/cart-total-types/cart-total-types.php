<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WOOPCD_PartialCOD_Cart_Total_Types' ) ) {
    WOOPCD_PartialCOD_Main::required_paths( dirname( __FILE__ ), array( 'cart-total-types.php' ) );

    class WOOPCD_PartialCOD_Cart_Total_Types {

        private static $instance = null;

        private static function get_instance() {
            if ( null == self::$instance ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public static function get_totals( $args, $cart_data ) {
            $options = self::get_instance()->get_option( $args );
            $totals = 0;

            foreach ( $options as $option ) {

                switch ( $option ) {
                    case 'subtotal':
                    case 'subtotal_tax':
                        $cart_subtotals = new WOOPCD_PartialCOD_Cart_Total_Types_Subtotals();
                        $totals = $cart_subtotals->calc_totals( $totals, $option, $cart_data );
                        break;

                    default:

                        if ( defined( 'WOOPCD_PARTIALCOD_PREMIUM' ) ) {
                            $prem_cart_totals = new WOOPCD_PartialCOD_Premium_Cart_Total_Types();
                            $totals = $prem_cart_totals->get_totals( $totals, $option, $cart_data );
                        }
                        break;
                }



                if ( has_filter( 'woopcd_partialcod/calculate-cart-' . $option . '-totals' ) ) {
                    $totals = apply_filters( 'woopcd_partialcod/calculate-cart-' . $option . '-totals', $totals, $cart_data );
                }
            }

            if ( $totals < 0 ) {
                $totals = 0;
            }

            return $totals;
        }

        private function get_option( $args ) {
            $opts = array();

            if ( 'cart-discounts' == $args[ 'module' ] ) {
                $opts = WOOPCD_PartialCOD::get_option( 'cart_discount_cart_totals', $opts );
            } else if ( 'cart-fees' == $args[ 'module' ] ) {
                $opts = WOOPCD_PartialCOD::get_option( 'cart_fee_cart_totals', $opts );
            } else if ( 'partial-payment' == $args[ 'module' ] ) {
                $opts = WOOPCD_PartialCOD::get_option( 'riskfree_cart_totals', $opts );
            } else if ( 'method-options' == $args[ 'module' ] ) {
                $opts = WOOPCD_PartialCOD::get_option( 'method_cart_totals', $opts );
            }

            if ( count( $opts ) == 0 ) {
                $opts = $this->get_default_options();
            }

            $options = array();
            foreach ( $opts as $opt ) {

                if ( isset( $opt[ 'include' ] ) ) {
                    $options[ $opt[ 'option_id' ] ] = $opt[ 'include' ];
                } else {
                    $options[ $opt[ 'option_id' ] ] = array();
                }
            }

            if ( isset( $options[ $args[ 'option_id' ] ] ) ) {
                return $options[ $args[ 'option_id' ] ];
            }
            return $options[ $opts[ 0 ][ 'option_id' ] ];
        }

        private function get_default_options() {
            return array(
                array(
                    'option_id' => '2234343',
                    'include' => array( 'subtotal', 'subtotal_tax' ),
                ),
                array(
                    'option_id' => '2234344',
                    'include' => array( 'subtotal' ),
                ),
            );
        }

    }

}