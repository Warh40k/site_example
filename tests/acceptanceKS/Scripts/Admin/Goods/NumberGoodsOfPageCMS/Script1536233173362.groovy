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
import internal.GlobalVariable as GlobalVariable
import org.openqa.selenium.WebDriver as WebDriver
import org.openqa.selenium.WebElement as WebElement
import com.kms.katalon.core.webui.driver.DriverFactory as DriverFactory
import org.junit.After as After
import org.openqa.selenium.By as By
import org.openqa.selenium.By.ByClassName as ByClassName
import org.openqa.selenium.By.ByXPath as ByXPath
import data.GoodsData as GoodsData

WebUI.click(findTestObject('CMS/Goods/CreateNewModifications/div_catalog'))

WebUI.click(findTestObject('Object Repository/CMS/Goods/div_settings'))

WebUI.setText(findTestObject('CMS/Goods/NumberGoodsOnPageCMS/input_number_goods_on_page_cms'), '1')

WebUI.click(findTestObject('CMS/Goods/NumberGoodsOnPageCMS/button_save'))

WebUI.click(findTestObject('CMS/Goods/div_goods'))

WebUI.delay(3)

WebDriver driver = DriverFactory.getWebDriver()

WebElement containerGoods = driver.findElement(By.className('sk-tab-list'))

//WebElement containerGoods = driver.findElement(By.className('x-panel-body   x-grid-body x-panel-body-default x-panel-body-default x-docked-noborder-right x-docked-noborder-left x-layout-fit'))

ArrayList<WebElement> Goods = new ArrayList<WebElement>()

//Goods.addAll(containerGoods.findElements(By.xpath('/div[2]//table//tr[contains(@class,\'x-grid-row\')]')))

Goods.addAll(containerGoods.findElements(By.xpath('//div[contains(@class,\'sk-tab-list\')]//table//tr')))

int countGoods = Goods.size()

countGoods = countGoods - 1 

if (countGoods != 1) {
	return err //driver.close()
}

