package com.ucm.pageobjects;

import java.util.List;

import org.apache.log4j.Logger;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.How;
import org.openqa.selenium.support.PageFactory;
import com.mongodb.operation.DropDatabaseOperation;
import com.ucm.config.BaseClass;

/**
 * This is Page Class for sub form creation . It contains all the elements and actions
 * related to sub form creation view.
 * 
 */

public class AdminUcmFormCreationPage extends BaseClass {

	private WebDriver driver;
	static Logger log = Logger.getLogger(AdminUcmFormCreationPage.class);

	public AdminUcmFormCreationPage(WebDriver driver) {
		this.driver = driver;
		PageFactory.initElements(driver, this);
	}
	/*
	 * Locators for 
	 */

	@FindBy(how = How.XPATH, using ="//a[text()='Types']")
	public WebElement Type; 
	@FindBy(how = How.XPATH, using ="//*[@id='toolbar-new']")
	public WebElement newType;
	@FindBy(how = How.NAME, using="jform[title]")
	public WebElement titleName;
	@FindBy(how = How.XPATH, using = "//input[@id='jform_params_allowed_count']")
	public WebElement allow_count;
	@FindBy(how = How.XPATH, using = "//*[@id=\"toolbar-apply\"]/button")
	public WebElement save;
	@FindBy(how = How.XPATH, using="//a[text()='Permissions']")
	public WebElement permission;
	@FindBy(how = How.XPATH, using = "//select[@id='jform_rules_core.type.createitem_1']")
	public WebElement permissionselect;
	@FindBy(how = How.XPATH, using="//select[@id='jform_rules_core.type.createitem_1']//option [@value=1]")
	public WebElement selectone;
	@FindBy(how = How.XPATH, using="//a[@href='#permission-2']")
	public WebElement permission2;
	@FindBy(how = How.XPATH, using="//select[@id='jform_rules_core.type.createitem_2']")
	public WebElement permission_2;
	@FindBy(how = How.XPATH, using="//select[@id='jform_rules_core.type.createitem_2']//option [@value=1]")
	public WebElement selectoneforregister;
	@FindBy(how = How.XPATH, using="//select[@id='jform_rules_core.type.viewitem_2']")
	public WebElement viewAll;
	@FindBy(how = How.XPATH, using="//select[@id='jform_rules_core.type.viewitem_2']//option[@value=1]")
	public WebElement selectviewAllallow;
	@FindBy(how = How.XPATH, using ="//button[@class='btn btn-small button-save']")
	public WebElement save_close_button; 
	@FindBy(how = How.XPATH, using = "//table[@id='typeList']//tr[contains(@class,'row')]//a[text()='Field Group']")
	public List<WebElement> field_groupcount;
	@FindBy(how = How.XPATH, using = "//button[@class='btn btn-small button-new btn-success']")
	public WebElement save_field_group;
	@FindBy(how = How.NAME, using ="jform[name]")
	public WebElement group_name;
	@FindBy(how = How.XPATH, using = "//button[@class='btn btn-small button-save']")
	public WebElement save_groupname;
	@FindBy(how = How.XPATH, using = "//ul[@id='submenu']//a[text()='Types']")
	public WebElement click_type;
	@FindBy(how = How.XPATH, using= "//table[@id='typeList']//tr[contains(@class,'row')]//a[text()='Fields']")
	public List<WebElement> field_typecount;
	@FindBy(how = How.XPATH, using="//button [@class='btn btn-small button-new btn-success']")
	public WebElement click_field;
	
	
	/*
	 * 
	 * Method for formcreation
	 * 
	 */

	public AdminUcmFormCreationPage ucmForm(String tn, String co, String gn) {
		
		Type.click(); 
		logger.pass("Click at type button");
		newType.click();
		logger.pass("click at newtype link");
		enterValue(titleName, tn);
		logger.pass("enter title name");
		enterValue(allow_count,co);
		logger.pass("enter allow count");
		save.click();
		logger.pass("save the ucm type");
		logger.pass("enter at save button");
		permission.click();
		logger.pass("Click on permission tab");
		permissionselect.click();
		logger.pass("click at create item");
		selectone.click();
		logger.pass("select allow for public createitem");
		permission2.click();
		logger.pass("click on registration tab");
		permission_2.click();
		logger.pass("click at createitem dropdonw for register user");
		selectoneforregister.click();
		logger.pass("select allow for register user");
		viewAll.click();
		logger.pass("select view all option from dropdown");
		selectviewAllallow.click();
		logger.pass("Select allow option");
		System.out.println("Subform created");
		logger.pass("Subform created");
		save_close_button.click();
		logger.pass("click at save and close button");
		List<WebElement> NumberofTypesfield = field_groupcount;
		for(int i=0;i<NumberofTypesfield.size();i++)
		{
			
			if(i==0)
			{
				WebElement singleFieldgroup = NumberofTypesfield.get(i);
//				Thread.sleep(2000);
				singleFieldgroup.click();
			}
		}
		logger.pass("select the 1st field group in the listing page");
		save_field_group.click();
		logger.pass("click at new button to createa a new group");
		enterValue(group_name, gn);
		logger.pass("enter sub group name");
		save_groupname.click();
		logger.pass("click at save button to save group name");
		click_type.click();
		logger.pass("click at type");
		List<WebElement> NumberOfTypesForFieldssub = field_typecount;
		for (int j=0; j<NumberOfTypesForFieldssub.size();j++)
		{
			if(j==0){
				WebElement singleField = NumberOfTypesForFieldssub.get(j); 
			singleField.click();
			}
		}
		click_field.click();
		logger.pass("click at 1st field groupin the listing page");
		return new AdminUcmFormCreationPage(driver);
	}
}
