package validate;

import java.io.IOException;
import java.util.ArrayList;

import org.apache.poi.openxml4j.exceptions.InvalidFormatException;

import config.PropertiesManager;
import io.ExcelFileManager;

public class ExcelValidator {
	
	public static void main(String[] args) throws InvalidFormatException, IOException {
		ExcelFileManager fileManager = new ExcelFileManager(new PropertiesManager());
		ArrayList<String> columns = fileManager.getColumns(false);
	}
	
}
