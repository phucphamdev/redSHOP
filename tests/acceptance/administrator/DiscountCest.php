<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\DiscountSteps;

/**
 * Class ManageDiscountAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class DiscountCest
{
	/**
	 * DiscountCest constructor.
	 */
	public function __construct()
	{
		$this->faker             = Faker\Factory::create();
		$this->discountName      = 'Discount' . rand(1, 100);
		$this->amount            = $this->faker->numberBetween(100, 999);
		$this->discountAmount    = $this->faker->numberBetween(10, 100);
		$this->newAmount         = $this->faker->numberBetween(100, 999);
		$this->startDate         = '13-06-2017';
		$this->endDate           = '13-08-2017';
		$this->shopperGroup      = 'Default Private';
		$this->discountType      = 0;
		$this->discountCondition = 1;
	}

	/**
	 * Function to Test Discount Creation in Backend
	 *
	 * @param   AcceptanceTester  $I         Acceptance Tester case.
	 * @param   string            $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function createDiscount(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->addDiscount($this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->discountCondition);
		$I->searchDiscount($this->discountName);
	}

	/**
	 * Function to Test Discount Deletion
	 *
	 * @depends createDiscount
	 */
	public function deleteDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Deletion of Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->deleteDiscount($this->discountName, $this->newAmount);
		$I->dontSeeElement(['link' => $this->discountName]);
	}

	/**
	 * Function add Discount with save button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends deleteDiscount
	 */
	public function addDiscountSave(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Discount creation with save button in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->addDiscountSave($this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType);
	}

	/**
	 * Function change state of Discount is Ubpublish
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends addDiscountSave
	 */
	public function unpublishChangeDiscountState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('UnPublish Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Unpublish Discount ');
		$I->changeDiscountState($this->discountName);
		$I->waitForText(\DiscountPage::$messageItemUnpublishSuccess, 60, \DiscountPage::$saveSuccess);
		$currentState = $I->getDiscountState($this->discountName);
		$I->verifyState('unpublished', $currentState);
	}

	/**
	 * Function change state of Discount is Publish
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends unpublishChangeDiscountState
	 */
	public function publishChangeDiscountState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Publish Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Unpublish Discount ');
		$I->changeDiscountState($this->discountName);
		$currentState = $I->getDiscountState($this->discountName);
		$I->verifyState('published', $currentState);
	}

	/**
	 * Function change state of Discount is Unpublish when used Unpublish button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends publishChangeDiscountState
	 */
	public function unpublishDiscountStateButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('UnPublish Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Unpublish Discount ');
		$I->unpublishDiscountStateButton($this->discountName);
		$currentState = $I->getDiscountState($this->discountName);
		$I->verifyState('unpublished', $currentState);
	}

	/**
	 * Function change state of Discount is publish when used publish button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends unpublishDiscountStateButton
	 */
	public function publishDiscountStateButton(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Publish Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Unpublish Discount ');
		$I->publishDiscountStateButton($this->discountName);
		$currentState = $I->getDiscountState($this->discountName);
		$I->verifyState('published', $currentState);
	}

	/**
	 * Function unpublish all discount when used publish button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends publishDiscountStateButton
	 */
	public function unpublishAllDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Unpublish All Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Unpublish all Discount ');
		$I->unpublishAllDiscount();
		$currentState = $I->getDiscountState($this->discountName);
		$I->verifyState('unpublished', $currentState);
	}

	/**
	 * Function publish all discount when used publish button
	 *
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 *
	 * @depends unpublishAllDiscount
	 */
	public function publishAllDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Publish All Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('publish all Discount ');
		$I->publishAllDiscount();
		$currentState = $I->getDiscountState($this->discountName);
		$I->verifyState('published', $currentState);
	}

	public function addDiscountCancel(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Discount creation with save button in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Create a Discount');
		$I->addDiscountCancel();
	}

	public function addDiscountStartThanEnd(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Discount creation with save button in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Create a Discount');
		$I->addDiscountStartThanEnd($this->discountName, $this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate);
	}

	public function addDiscountMissingName(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Discount creation with save button in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Create a Discount');
		$I->addDiscountMissingName($this->amount, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate);
	}

	public function addDiscountMissingAmount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Discount creation with save button in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Create a Discount');
		$I->addDiscountMissingAmount($this->discountName, $this->discountAmount, $this->shopperGroup, $this->discountType, $this->startDate, $this->endDate);
	}

	public function addDiscountMissingShopperGroups(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Discount creation with save button in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Create a Discount');
		$I->addDiscountMissingShopperGroups($this->discountName, $this->amount, $this->discountAmount, $this->discountType, $this->startDate, $this->endDate);
	}

	public function addDiscountWithAllFieldsEmpty(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Discount creation with save button in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Create a Discount');
		$I->addDiscountWithAllFieldsEmpty();
	}

	/**
	 * Function to Test Discount Updation in the Administrator
	 *
	 * @depends createDiscount
	 */
	public function updateDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Discount gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->wantTo('Update Existing Discount');
		$I->editDiscount($this->discountName, $this->amount, $this->newAmount);
	}

	public function deleteAllDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Deletion of Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->deleteAllDiscount();
		$I->dontSeeElement(['link' => $this->discountName]);
	}

	public function checkEditButton(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Deletion of Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->checkEditButton();
	}

	public function checkDeleteButton(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Deletion of Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->checkDeleteButton();
	}

	public function checkPublishButton(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Deletion of Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->checkPublishButton();
	}

	public function checkUnpublishButton(AcceptanceTester $I, $scenario)
	{
		$I->wantToTest('Deletion of Discount in Administrator');
		$I->doAdministratorLogin();
		$I = new DiscountSteps($scenario);
		$I->checkUnpublishButton();
	}
}
