<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
session_start();
if(get_option('amgt_paymaster_pack')=="yes" && is_plugin_active('apartment-management/apartment-management.php'))
{
    $_SESSION['action_type']= 'frontend_invoice_payment';
    $_SESSION['invoice_id']= $_POST['invoice_id'];
    $_SESSION['paid_amount']= $_POST['amount'];
    $plan_name='Invoice Payment';
    $pay_id = $_REQUEST['invoice_id'];
    $amount = $_REQUEST['amount'];
    $payment_method = $_REQUEST['payment_method'];
    $customer_id = $_REQUEST['member_id'];
    $plan_amount=$_POST['amount'] * 100;
    $currency = get_option('apartment_currency_code');
    $success_url = "?apartment-dashboard=user&page=accounts&tab=invoice-list&action=success";
    $cencal_url = "?apartment-dashboard=user&page=accounts&tab=invoice-list&action=cancel";
}
if(get_option('smgt_paymaster_pack')=="yes" && is_plugin_active('school-management/school-management.php'))
{
    $_SESSION['action']= 'mj_smgt_student_add_payment';
    $_SESSION['fees_pay_id']= $_POST['fees_pay_id'];
    $_SESSION['amount']= $_POST['amount'];
    $plan_name='Invoice Payment';
    $pay_id = $_REQUEST['fees_pay_id'];
    $amount = $_REQUEST['amount'];
    $payment_method = $_REQUEST['payment_method'];
    $customer_id = $_REQUEST['student_id'];
    $plan_amount=$_POST['amount'] * 100;
    $currency = get_option('smgt_currency_code');
    $success_url = "?dashboard=user&page=feepayment&tab=feepaymentlist&action=success";
    $cencal_url = "?dashboard=user&page=feepayment&tab=feepaymentlist&action=cancel";
}
if(is_plugin_active('church-management/church-management.php'))
{
    $_SESSION['action']= 'mj_cmgt_church_donation_payment';
    $_SESSION['member_id']= $_POST['member_id'];
    $_SESSION['amount']= $_POST['amount'];
    $plan_name='Donation Payment';
    $pay_id = $_REQUEST['member_id'];
    $amount = $_REQUEST['amount'];
    $payment_method = $_REQUEST['pay_method'];
    $customer_id = $_REQUEST['member_id'];
    $plan_amount=$_POST['amount'] * 100;
    $currency = get_option('cmgt_currency_code');
    $success_url = home_url()."?church-dashboard=user&page=donate&action=success";
    $cencal_url = home_url()."?church-dashboard=user&page=donate&action=cancel";
}

$key_id=get_option( 'razorpay__key' );
$key_secret=get_option( 'razorpay_secret_mid' ); 
$current_user_id = get_current_user_id();
$user_info = get_userdata($current_user_id);
$member_id = $user_info->ID;
$user_name = $user_info->display_name;
$user_email = $user_info->user_email;
$contact_number = $user_info->contact_number;
$plan_description='';

$subdata = array(
    'amount' => $plan_amount,
    'currency' => "INR",
    'receipt' => 'receipt#101'
    );
$suburl = 'https://api.razorpay.com/v1/orders'; //create order url 
$subparams = http_build_query($subdata);
//CURL Request
$subch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($subch, CURLOPT_URL, $suburl);
curl_setopt($subch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
curl_setopt($subch, CURLOPT_TIMEOUT, 60);
curl_setopt($subch, CURLOPT_POST, 1);
curl_setopt($subch, CURLOPT_POSTFIELDS, $subparams);
curl_setopt($subch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($subch, CURLOPT_SSL_VERIFYPEER, true);
$subResult = curl_exec($subch);
$subres = json_decode($subResult);
$admin_url=admin_url( 'admin-ajax.php' );
// var_dump($subres->id);
// die;
?>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  
    var options = {
        "key": "<?php echo $key_id; ?>", // Enter the Key ID generated from the Dashboard
        "amount": <?php echo $plan_amount;?>, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
        "currency": "INR",
        "name": "<?php echo $plan_name; ?>",
        "description": "<?php echo $plan_description; ?>",
        "order_id": "<?php echo $subres->id;?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
        "prefill": {
        "name": "<?php echo $user_name; ?>",
        "email": "<?php echo $user_email; ?>",
        "contact": "<?php echo $contact_number;?>"
        },
        "handler": function (response){
      
            jQuery.ajax({
                type: "POST",
                url: "<?php echo $admin_url; ?>", //this is wordpress ajax file which is already avaiable in wordpress
                data: {
                    "action": 'get_data_razorpay', //this value is first parameter of add_action,
                    "invoice_id": "<?php echo $pay_id; ?>",
                    "amount": "<?php echo $amount; ?>",
                    "payment_method": "<?php echo $payment_method; ?>",
                    "donetion_type": "<?php echo $_REQUEST['donetion_type']; ?>",
                    "description": "<?php echo $_REQUEST['description']; ?>",
                    "transaction_id": "<?php echo $subres->id; ?>",
                    "member_id": "<?php echo $customer_id; ?>",
                    "created_by": "<?php echo $customer_id; ?>"
                },
                success: function(data){ 
                 
                    var result = 1;
                    var success_url = "<?php echo $success_url ?>";
                    var cancel_url = "<?php echo $cencal_url ?>";
                    if(data == result)
                    {
                        window.location.href = success_url;
                    }
                    else
                    {
                        window.location.href = cancel_url;
                    }
                },
            });
        
        },
        "notes": {
        "address": ""
        },
        "theme": {
        "color": "#3399cc"
        }
    };
    
    var rzp1 = new Razorpay(options);

    rzp1.open();
    e.preventDefault();
</script>