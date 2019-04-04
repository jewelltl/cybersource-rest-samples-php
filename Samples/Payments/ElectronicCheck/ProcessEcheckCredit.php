<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '../../../vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '../../../Resources/ExternalConfiguration.php';

function ProcessEcheckCredit($flag)
{
    $commonElement = new CyberSource\ExternalConfiguration();
    $config = $commonElement->ConnectionHost();
	$merchantConfig = $commonElement->merchantConfigObject();
	$apiclient = new CyberSource\ApiClient($config, $merchantConfig);
    $api_instance = new CyberSource\Api\CreditApi($apiclient);
	
    $cliRefInfoArr = [
		"code" => "test_credits"
	];
    $client_reference_information = new CyberSource\Model\Ptsv2paymentsClientReferenceInformation($cliRefInfoArr);

    $amountDetailsArr = [
		"totalAmount" => "200",
		"currency" => "usd"
	];
    $amountDetInfo = new CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails($amountDetailsArr);
	
    $billtoArr = [
		"firstName" => "John",
		"lastName" => "Doe",
		"address1" => "1 Market St",
		"postalCode" => "94105",
		"locality" => "san francisco",
		"administrativeArea" => "CA",
		"country" => "US",
		"phoneNumber" => "4158880000",
		"company" => "ABC Company",
		"email" => "test@cybs.com"
	];
    $billto = new CyberSource\Model\Ptsv2paymentsOrderInformationBillTo($billtoArr);
	
    $orderInfoArr = [
		"amountDetails" => $amountDetInfo, 
		"billTo" => $billto
	];
    $order_information = new CyberSource\Model\Ptsv2paymentsOrderInformation($orderInfoArr);

    $accountInfo = [
		"number" => "4100",
		"type" => "C",
		"checkNumber" => "123456"
	];

    $bankInfo = [
		"account" => new CyberSource\Model\Ptsv2paymentsPaymentInformationBankAccount($accountInfo), 
		"routingNumber" => "071923284"
	];
    $bank = new CyberSource\Model\Ptsv2paymentsPaymentInformationBank($bankInfo);

    $paymentInfoArr = [
		"bank" => $bank
	];
    $payment_information = new CyberSource\Model\Ptsv2paymentsPaymentInformation($paymentInfoArr);

    $paymentRequestArr = [
		"clientReferenceInformation" => $client_reference_information, 
		"orderInformation" => $order_information, 
		"paymentInformation" => $payment_information
	];

    $paymentRequest = new CyberSource\Model\CreateCreditRequest($paymentRequestArr);
	
    $api_response = list($response, $statusCode, $httpHeader) = null;
    try
    {
        $api_response = $api_instance->createCredit($paymentRequest);
        if ($flag == true)
        {
            //Returning the ID
            echo "Fetching Credit ID: " . $api_response[0]['id'] . "\n";
            return $api_response[0]['id'];
        }
        else
        {
            print_r($api_response);
        }
    }
    catch(Cybersource\ApiException $e)
    {
        print_r($e->getResponseBody());
        print_r($e->getMessage());
    }
}

// Call Sample Code
if (!defined('DO_NOT_RUN_SAMPLES'))
{
    echo "Process Credit Samplecode is Running.. \n";
    ProcessEcheckCredit(false);
}

?>
