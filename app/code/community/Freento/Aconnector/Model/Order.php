<?php

class Freento_Aconnector_Model_Order
{
    public $_orderAttributes = array(
        'entity_id' => array('label' => 'ID', 'type' => 'numeric'),
        'increment_id' => array('label' => 'Increment ID', 'type' => 'string'),
        'state' => array('label' => 'State', 'type' => 'string'),
        'status' => array('label' => 'Status', 'type' => 'string'),
        'coupon_code' => array('label' => 'Coupon Code', 'type' => 'string'),
        'customer_id' => array('label' => 'Customer ID', 'type' => 'numeric'),
        'base_discount_amount' => array('label' => 'Base Discount Amount', 'type' => 'price'),
        'discount_description' => array('label' => 'Discount Description', 'type' => 'string'),
        'base_grand_total' => array('label' => 'Base Grand Total', 'type' => 'price'),
        'base_shipping_amount' => array('label' => 'Base Shipping Amount', 'type' => 'price'),
        'base_subtotal' => array('label' => 'Base Subtotal', 'type' => 'price'),
        'base_tax_amount' => array('label' => 'Base Tax Amount', 'type' => 'price'),
        'base_total_canceled' => array('label' => 'Base Total Cancelled', 'type' => 'price'),
        'base_total_invoiced' => array('label' => 'Base Total Invoiced', 'type' => 'price'),
        'base_total_invoiced_cost' => array('label' => 'Base Total Invoiced Cost', 'type' => 'price'),
        'base_total_paid' => array('label' => 'Base Total Paid', 'type' => 'price'),
        'base_total_qty_ordered' => array('label' => 'Base Total Qty Ordered', 'type' => 'numeric'),
        'base_total_refunded' => array('label' => 'Base Total Refunded', 'type' => 'price'),
        'shipping_amount' => array('label' => 'Shipping Amount', 'type' => 'price'),
        'subtotal' => array('label' => 'Subtotal', 'type' => 'price'),
        'tax_amount' => array('label' => 'Tax Amount', 'type' => 'price'),
        'total_canceled' => array('label' => 'Total Cancelled', 'type' => 'price'),
        'total_invoiced' => array('label' => 'Total Invoiced', 'type' => 'price'),
        'base_total_invoiced_cost' => array('label' => 'Base Total Invoiced Cost', 'type' => 'price'),
        'total_paid' => array('label' => 'Total Paid', 'type' => 'price'),
        'total_qty_ordered' => array('label' => 'Total Qty Ordered', 'type' => 'numeric'),
        'total_refunded' => array('label' => 'Total Refunded', 'type' => 'price'),
        'email_sent' => array('label' => 'Email Sent', 'type' => 'string'),
        'customer_email' => array('label' => 'Customer Email', 'type' => 'string'),
        'customer_prefix' => array('label' => 'Customer Prefix', 'type' => 'string'),
        'customer_firstname' => array('label' => 'Customer Firstname', 'type' => 'string'),
        'customer_middlename' => array('label' => 'Customer Middlename', 'type' => 'string'),
        'customer_lastname' => array('label' => 'Customer Lastname', 'type' => 'string'),
        'customer_suffix' => array('label' => 'Customer Suffix', 'type' => 'string'),
        'customer_taxvat' => array('label' => 'Customer Taxvat', 'type' => 'string'),
        'customer_dob' => array('label' => 'Customer Date of Birth', 'type' => 'date'),
        'order_currency_code' => array('label' => 'Order Currency Code', 'type' => 'string'),
        'shipping_method' => array('label' => 'Shipping Method', 'type' => 'string'),
        'customer_note' => array('label' => 'Customer Note', 'type' => 'string'),
        'created_at' => array('label' => 'Created At', 'type' => 'datetime'),
        'updated_at' => array('label' => 'Updated At', 'type' => 'datetime'),
        'total_item_count' => array('label' => 'Total Item Count', 'type' => 'numeric'),
        'coupon_rule_name' => array('label' => 'Coupon Rule Name', 'type' => 'string'),
    );
    
    public function getAttributes()
    {
        return $this->_orderAttributes;
    }

    public function getAttributesList()
    {
        $toReturn = array();
        foreach ($this->getAttributes() as $attributeCode => $attribute) {
            $toReturn[] = array(
                'id' => $attributeCode,
                'attribute_code' => $attributeCode,
                'attribute_label' => $attribute['label'],
                'attribute_type' => $attribute['type']
            );
        }
        return array('main' => $toReturn);
    }
}
