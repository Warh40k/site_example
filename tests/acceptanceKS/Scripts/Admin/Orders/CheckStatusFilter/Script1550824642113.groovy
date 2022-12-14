import static com.kms.katalon.core.checkpoint.CheckpointFactory.findCheckpoint
import static com.kms.katalon.core.testcase.TestCaseFactory.findTestCase
import static com.kms.katalon.core.testdata.TestDataFactory.findTestData
import static com.kms.katalon.core.testobject.ObjectRepository.findTestObject
import com.kms.katalon.core.checkpoint.Checkpoint as Checkpoint
import com.kms.katalon.core.cucumber.keyword.CucumberBuiltinKeywords as CucumberKW
import com.kms.katalon.core.mobile.keyword.MobileBuiltInKeywords as Mobile
import com.kms.katalon.core.model.FailureHandling as FailureHandling
import com.kms.katalon.core.testcase.TestCase as TestCase
import com.kms.katalon.core.testdata.TestData as TestData
import com.kms.katalon.core.testobject.TestObject as TestObject
import com.kms.katalon.core.webservice.keyword.WSBuiltInKeywords as WS
import com.kms.katalon.core.webui.keyword.WebUiBuiltInKeywords as WebUI
import com.kms.katalon.core.exception.StepErrorException as StepErrorException
import org.openqa.selenium.WebDriver as WebDriver
import org.openqa.selenium.WebElement as WebElement
import com.kms.katalon.core.webui.driver.DriverFactory as DriverFactory
import org.junit.After as After
import org.openqa.selenium.By as By
import org.openqa.selenium.By.ByClassName as ByClassName
import org.openqa.selenium.By.ByXPath as ByXPath
import internal.GlobalVariable as GlobalVariable

WebUI.verifyElementPresent(findTestObject('Object Repository/CMS/Orders/OrdersList/div_second_order_list'), 1)

WebUI.click(findTestObject('Object Repository/CMS/Orders/OrdersList/img_edit_first_order'))

WebUI.click(findTestObject('Object Repository/CMS/Orders/DetailOrder/div_list_type_status'))

status = WebUI.getText(findTestObject('Object Repository/CMS/Orders/DetailOrder/li_last_satus_list'))

WebUI.click(findTestObject('Object Repository/CMS/Orders/DetailOrder/li_last_satus_list'))

WebUI.click(findTestObject('Object Repository/CMS/Orders/DetailOrder/span_save_order'))

WebUI.click(findTestObject('Object Repository/CMS/Orders/OrdersList/span_status_filter'))

WebDriver driver = DriverFactory.getWebDriver()

driver.findElement(By.xpath('//div[contains(@class,\'x-panel x-menu x-panel-default x-layer\')]//span[text() = \'' + status + '\']')).click()

WebUI.delay(1)

WebUI.verifyElementNotPresent(findTestObject('Object Repository/CMS/Orders/OrdersList/div_second_order_list'), 1)

namestatus = WebUI.getText(findTestObject('Object Repository/CMS/Orders/OrdersList/div_status_first_order'))

if (namestatus != status) {
	throw new StepErrorException('???? ?????? ???????????? ????????????????????????????!')
}