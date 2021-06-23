<?php
defined( 'ABSPATH' ) || exit;
?>

<?php foreach ($coleteonline_shipping_meta as $shipping_meta_data):
  $shipping_data = $shipping_meta_data->get_data()['value'];
?>
  <div class="coleteonline-courier-order">
    <div class="coleteonline-courier-service">
      <?php
        echo $shipping_data['service']['service']['courierName'] . ' ' .
              $shipping_data['service']['service']['name'];
      ?>
    </div>
    <div class="coleteonline-courier-price">
      <span class="coleteonline-courier-price-total">
        <?php
          echo $shipping_data['service']['price']['total'] . ' ron';
        ?>
      </span>
      <span class="coleteonline-courier-price-no-vat">
        <?php
          echo '(' . $shipping_data['service']['price']['noVat'] . ' ron + TVA)';
        ?>
      </span>
    </div>
    <div class="coleteonline-courier-identification">
      <div class="coleteonline-courier-awb">
      <?php
        echo $shipping_data['awb'];
      ?>
      </div>
      <div class="coleteonline-courier-unique-id">
        <?php
          echo $shipping_data['uniqueId'];
        ?>
      </div>
    </div>
    <div class="coleteonline-courier-actions">
      <button type="button"
        class="button button-primary coleteonline-do-download-awb"
        data-unique-id="<?php echo $shipping_data['uniqueId']; ?>"
      >
        <?php echo __("Download AWB", "coleteonline"); ?>
      </button>
      <div class="coleteonline-file-download-loading">
        <div class="coleteonline-lds-ring">
          <div></div><div></div><div></div><div></div>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>