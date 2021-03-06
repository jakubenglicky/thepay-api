<?php declare(strict_types = 1);

namespace Tp;

/**
 * Class representing one payment instance.
 */
class Payment
{

	/**
	 * Config containing merchant-specific configuration options.
	 * Instance of Tp\TpMerchantConfig, passed to the Tp\TpPayment constructor.
	 *
	 * @var MerchantConfig
	 */
	protected $config;

	/** @var float|null value indicating the amount of money that should be paid. */
	protected $value = null;

	/** @var string|null Currency identifier. */
	protected $currency = null;

	/** @var string|null Payment description that should be visible to the customer. */
	protected $description = null;

	/**
	 * Any merchant-specific data, that will be returned to the site after
	 * the payment has been completed.
	 *
	 * @var string|null
	 */
	protected $merchantData = null;

	/**
	 * URL where to redirect the user after the payment has been completed.
	 * It defaults to value configured in administration interface, but
	 * can be overwritten using this property.
	 *
	 * @var string|null
	 */
	protected $returnUrl = null;

	/**
	 * Target of the “Back to e-shop” button on the offline payment info page.
	 * If not set, defaults to the account URL. Must be a valid HTTP or HTTPS
	 * URL.
	 *
	 * @var string|null
	 */
	protected $backToEshopUrl = null;

	/**
	 * ID of payment method to use for paying. Setting this argument should
	 * be result of user's selection, not merchant's selection.
	 *
	 * @var int|null
	 */
	protected $methodId = null;

	/**
	 * @var string|null
	 * @deprecated
	 */
	protected $customerData = null;

	/**
	 * @var string|null Customer’s e-mail address. Used to send payment info and payment link from the payment info
	 *      page.
	 */
	protected $customerEmail = null;

	/**
	 * @var bool|null If card payment will be charged immediately or only blocked and charged later by paymentDeposit
	 *      operation.
	 */
	protected $deposit = null;

	/** @var bool|null If card payment is recurring. */
	protected $isRecurring = null;

	/** @var string|null numerical specific symbol (used only if payment method supports it). */
	protected $merchantSpecificSymbol = null;

	/** @var EetDph|null VAT decomposition for EET */
	protected $eetDph = null;

	/**
	 * Constructor. Create the payment.
	 *
	 * @param MerchantConfig $config containing merchant's access credentials to the ThePay system.
	 */
	public function __construct(MerchantConfig $config)
	{
		$this->config = $config;

		if ($this->returnUrl === null && isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
			$this->returnUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}
	}

	/**
	 * Sets the value property.
	 *
	 * @throws InvalidParameterException
	 */
	public function setValue(float $value): void
	{
		// Only positive numbers allowed.
		if ($value < 0) {
			throw new InvalidParameterException('value');
		}

		$this->value = $value;
	}

	public function setCurrency(string $currency): void
	{
		$this->currency = $currency;
	}

	/**
	 * Sets the description property.
	 */
	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

	/**
	 * Sets the merchantData property.
	 */
	public function setMerchantData(string $data): void
	{
		$this->merchantData = $data;
	}

	/**
	 * Sets the returnUrl property.
	 */
	public function setReturnUrl(string $returnUrl): void
	{
		$this->returnUrl = $returnUrl;
	}

	public function setBackToEshopUrl(?string $backToEshopUrl = null): void
	{
		$this->backToEshopUrl = $backToEshopUrl;
	}

	/**
	 * Sets the methodId property.
	 */
	public function setMethodId(int $methodId): void
	{
		$this->methodId = $methodId;
	}

	/**
	 * Return MerchantConfig associated with this payment.
	 */
	public function getMerchantConfig(): MerchantConfig
	{
		return $this->config;
	}

	/**
	 * Returns the value property. If value was not specified using
	 * setValue() method, NULL is returned.
	 */
	public function getValue(): ?float
	{
		return $this->value;
	}

	/**
	 * Returns the currency property. If currency was not specified using
	 * setCurrency() method, NULL is returned.
	 */
	public function getCurrency(): ?string
	{
		return $this->currency;
	}

	/**
	 * Returns the description property. If description was not specified
	 * using setDescription() method, NULL is returned.
	 */
	public function getDescription(): ?string
	{
		return $this->description;
	}

	/**
	 * Returns the merchantData property. If merchantData was not specified
	 * using setMerchantData() method, NULL is returned.
	 */
	public function getMerchantData(): ?string
	{
		return $this->merchantData;
	}

	/**
	 * Returns the returnUrl property. If returnUrl was not specified using
	 * setReturnUrl() method, NULL is returned.
	 */
	public function getReturnUrl(): ?string
	{
		return $this->returnUrl;
	}

	public function getBackToEshopUrl(): ?string
	{
		return $this->backToEshopUrl;
	}

	/**
	 * Returns the methodId property. If methodId was not specified using
	 * setMethodId() property, NULL is returned.
	 */
	public function getMethodId(): ?int
	{
		return $this->methodId;
	}

	public function setCustomerData(string $data): void
	{
		$this->customerData = $data;
	}

	/**
	 * @deprecated
	 * @return string previously set customer data
	 */
	public function getCustomerData(): ?string
	{
		return $this->customerData;
	}

	public function setCustomerEmail(?string $customerEmail): void
	{
		$this->customerEmail = $customerEmail;
	}

	public function getCustomerEmail(): ?string
	{
		return $this->customerEmail;
	}

	/**
	 * @return bool If card payment will be charged immediately or only blocked and charged later by paymentDeposit
	 *                 operation.
	 */
	public function getDeposit(): ?bool
	{
		return $this->deposit;
	}

	/**
	 * Set if card payment will be charged immediately or only blocked and charged later by paymentDeposit operation.
	 */
	public function setDeposit(bool $deposit): void
	{
		$this->deposit = $deposit;
	}

	/**
	 * If card payment is recurring.
	 */
	public function getIsRecurring(): ?bool
	{
		return $this->isRecurring;
	}

	/**
	 * Set if card payment is recurring.
	 */
	public function setIsRecurring(bool $isRecurring): void
	{
		$this->isRecurring = $isRecurring;
	}

	/**
	 * Numerical specific symbol (used only if payment method supports it).
	 */
	public function getMerchantSpecificSymbol(): ?string
	{
		return $this->merchantSpecificSymbol;
	}

	/**
	 * Numerical specific symbol (used only if payment method supports it).
	 */
	public function setMerchantSpecificSymbol(string $merchantSpecificSymbol): void
	{
		$this->merchantSpecificSymbol = $merchantSpecificSymbol;
	}


	/**
	 * @return EetDph VAT decomposition for EET
	 */
	public function getEetDph(): ?EetDph
	{
		return $this->eetDph;
	}

	/**
	 * @param EetDph $eetDph VAT decomposition for EET
	 */
	public function setEetDph(?EetDph $eetDph = null): void
	{
		$this->eetDph = $eetDph;
	}

	/**
	 * List arguments to put into the URL. Returns associative array of
	 * arguments that should be contained in the ThePay gate call.
	 *
	 * @return mixed[]
	 */
	public function getArgs(): array
	{
		$input = [];

		$input['merchantId'] = $this->config->merchantId;
		$input['accountId'] = $this->config->accountId;

		$value = $this->value;
		if ($value !== null) {
			$input['value'] = number_format($value, 2, '.', '');
		}

		if ($this->currency !== null) {
			$input['currency'] = $this->currency;
		}

		if ($this->description !== null) {
			$input['description'] = $this->description;
		}

		if ($this->merchantData !== null) {
			$input['merchantData'] = $this->merchantData;
		}

		if ($this->customerData !== null) {
			$input['customerData'] = $this->customerData;
		}

		if ($this->customerEmail !== null) {
			$input['customerEmail'] = $this->customerEmail;
		}

		if ($this->returnUrl !== null) {
			$input['returnUrl'] = $this->returnUrl;
		}

		if ($this->backToEshopUrl !== null) {
			$input['backToEshopUrl'] = $this->backToEshopUrl;
		}

		if ($this->methodId !== null) {
			$input['methodId'] = $this->methodId;
		}

		if ($this->deposit !== null) {
			$input['deposit'] = $this->getDeposit() ? '1' : '0';
		}

		if ($this->isRecurring !== null) {
			$input['isRecurring'] = $this->getIsRecurring() ? '1' : '0';
		}

		if ($this->merchantSpecificSymbol !== null) {
			$input['merchantSpecificSymbol'] = $this->merchantSpecificSymbol;
		}

		if ($this->eetDph !== null && !$this->eetDph->isEmpty()) {
			$input = array_merge($input, $this->eetDph->toArray());
		}

		return $input;
	}

	/**
	 * Returns signature to authenticate the payment. The signature
	 * consists of hash of all specified parameters and the merchant
	 * password specified in the configuration. So no one can alter the
	 * payment, because the password is not known.
	 */
	public function getSignature(): string
	{
		$input = $this->getArgs();

		$items = [];
		foreach ($input as $key => $val) {
			$items[] = sprintf('%s=%s', $key, $val);
		}

		$items[] = 'password=' . $this->getMerchantConfig()->password;

		return self::hashFunction(implode('&', $items));
	}

	/**
	 * Function that calculates hash.
	 */
	public static function hashFunction(string $str): string
	{
		return md5($str);
	}

	public function __toString(): string
	{
		return sprintf(
			'TpGatePayment[value: %f; currency: %s; description: %s; merchantData: %s; returnUrl: %s; methodId: %d; deposit: %s; isRecurring: %s; merchantSpecificSymbol: %s]',
			$this->value,
			$this->currency,
			$this->description,
			$this->merchantData,
			$this->returnUrl,
			$this->methodId,
			$this->deposit ? 'true' : 'false',
			$this->isRecurring ? 'true' : 'false',
			$this->merchantSpecificSymbol
		);
	}

}
