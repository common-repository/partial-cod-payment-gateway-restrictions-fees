<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WOOPCD_PartialCOD_WOOCS_Discounts' ) ) {

    class WOOPCD_PartialCOD_WOOCS_Discounts {

        public function __construct() {

            add_filter( 'woopcd_partialcod/discount-amount', array( $this, 'get_discount_amount' ), 10, 3 );
            add_filter( 'woopcd_partialcod/discount-amount-tax', array( $this, 'get_discount_amount_tax' ), 10, 3 );
            add_filter( 'woopcd_partialcod/discount-amount-taxes', array( $this, 'get_discount_amount_taxes' ), 10, 3 );
            add_filter( 'woopcd_partialcod/discount-coupon-amount', array( $this, 'get_coupon_discount_amount' ), 10, 4 );
        }

        public function get_discount_amount( $discount_amount, $discount_key, $discount ) {

            $converter = $this->get_converter();

            return $converter->convert_amount( $discount_amount );
        }

        public function get_discount_amount_tax( $discount_amount_tax, $discount_key, $discount ) {

            $converter = $this->get_converter();

            return $converter->convert_amount( $discount_amount_tax );
        }

        public function get_discount_amount_taxes( $discount_taxes, $discount_key, $discount ) {

            $converter = $this->get_converter();

            return $converter->convert_amount_list( $discount_taxes );
        }

        public function get_coupon_discount_amount( $discount_amount, $coupon_code, $discount_key, $discount ) {

            $converter = $this->get_converter();

            return $converter->convert_amount( $discount_amount );
        }

        private function get_converter() {

            return WOOPCD_PartialCOD_WOOCS::get_instance();
        }

    }

    new WOOPCD_PartialCOD_WOOCS_Discounts();
}