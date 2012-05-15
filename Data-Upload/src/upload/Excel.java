package upload;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;


import org.apache.poi.hssf.usermodel.HSSFWorkbook;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.ss.usermodel.Sheet;
import org.apache.poi.ss.usermodel.Workbook;

public class Excel {
	
	private static final String CONFIG_FILE = "settings.properties";	

	public static void main(String[] args) {

		try {
			FileInputStream fileInput = new FileInputStream("");
			Workbook wb = new HSSFWorkbook(fileInput);
			
			Sheet sheet = wb.getSheetAt(0);
			Row firstRow = sheet.getRow(0);
			
			
		} catch (FileNotFoundException e) {			
			System.out.println(e.getMessage());
		} catch (IOException e) {
			System.out.println(e.getMessage());			
		}

	}

}
