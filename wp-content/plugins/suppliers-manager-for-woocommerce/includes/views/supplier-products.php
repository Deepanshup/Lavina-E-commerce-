
<?php
$supplier = new FT_SMFW_Supplier($post_id);
$products = $supplier->getProducts();
$nb_products = count($products);

$alert_stock_min = get_option('ft_smfw_alert_stock_min');
?>

<div class="wrap ft_smfw_supplier_products_page">
    <h1><?php echo sprintf(__("%s's products", FT_SMFW_TEXT_DOMAIN), $supplier->getName()); ?></h1>

    <?php FT_SMFW_editor::print_supplier_menu($post_id, 'products'); ?>

    <?php
    if ($nb_products) :
        ?>
        <table id="ft_smfw_supplier_products_table">
            <thead>
                <tr>
                    <!-- <th class="checkbox" /> -->
                    <th class="part-number-column"><?php _e("Part number", FT_SMFW_TEXT_DOMAIN); ?></th>
                    <th class="product-column"><?php _e("Product", FT_SMFW_TEXT_DOMAIN); ?></th>
                    <th class="stock-column"><?php _e("Stock", FT_SMFW_TEXT_DOMAIN); ?></th>
                    <th class="price-column"><?php _e("Supplier price", FT_SMFW_TEXT_DOMAIN); ?></th>
                    <th class="price-column"><?php _e("Sale price", FT_SMFW_TEXT_DOMAIN); ?></th>
                    <th class="packaging-column"><?php _e("Packaging", FT_SMFW_TEXT_DOMAIN); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) : ?>
                    <tr <?php if ($product->managing_stock() && $alert_stock_min >= $product->get_stock_quantity()) echo "class=\"warning-row\""; ?>>
                        <!-- <td>
                            <input type="checkbox" name="" value="">
                        </td> -->
                        <td>
                            <?php
                            $product_supplier_part_number = get_post_meta($product->get_id(), 'ft_smfw_supplier_part_number', true);
                            echo $product_supplier_part_number ? $product_supplier_part_number : "-";
                            ?>
                        </td>
                        <td>
                            <a href="<?php _e(get_edit_post_link($product->is_type('simple') ? $product->get_id() : $product->get_parent_id())); ?>" target="_blank">
                                <?php _e($product->get_name()); ?>
                            </a>
                            <small>
                                <?php
                                if ($product->is_type('simple')) {
                                    _e("Simple product", FT_SMFW_TEXT_DOMAIN);
                                } else {
                                    _e("Variant product", FT_SMFW_TEXT_DOMAIN);
                                }
                                ?>
                                -
                                <?php _e("SKU", FT_SMFW_TEXT_DOMAIN); ?> : <?php echo esc_html($product->get_sku() ? $product->get_sku() : __("-", FT_SMFW_TEXT_DOMAIN)); ?>
                            </small>
                        </td>
                        <td class="cell-text-right">
                            <?php echo $product->managing_stock() ? $product->get_stock_quantity() : __("-", FT_SMFW_TEXT_DOMAIN); ?>
                        </td>
                        <td class="cell-text-right">
                            <?php
                            $product_supplier_price = get_post_meta($product->get_id(), 'ft_smfw_supplier_price', true);
                            echo $product_supplier_price ? $product_supplier_price . get_woocommerce_currency_symbol() : __("-", FT_SMFW_TEXT_DOMAIN);
                            ?>
                        </td>
                        <td class="cell-text-right">
                            <?php
                            $product_price = $product->get_price();
                            echo $product_price ? $product_price . get_woocommerce_currency_symbol() : __("-", FT_SMFW_TEXT_DOMAIN);
                            ?>
                        </td>
                        <td class="cell-text-right">
                            <?php
                            $product_supplier_packaging = get_post_meta($product->get_id(), 'ft_smfw_supplier_packaging', true);
                            echo $product_supplier_packaging ? $product_supplier_packaging : __("-", FT_SMFW_TEXT_DOMAIN);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
    else:
        ?>
        <p>
            <?php _e("No product linked to this supplier…", FT_SMFW_TEXT_DOMAIN); ?>
            <a href="<?php _e(admin_url('edit.php?post_type=product')); ?>"><?php _e('Browse products', FT_SMFW_TEXT_DOMAIN); ?></a>
        </p>
        <?php
    endif;
    ?>
</div>
