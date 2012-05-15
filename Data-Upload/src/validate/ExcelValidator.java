package validate;

import java.io.IOException;
import java.sql.SQLException;
import java.util.ArrayList;

import org.apache.poi.openxml4j.exceptions.InvalidFormatException;

import config.PropertiesManager;
import dao.MySQLDAOManager;
import io.ExcelFileManager;

public class ExcelValidator {
	
	public static void main(String[] args) throws InvalidFormatException, IOException, ClassNotFoundException, SQLException {
		PropertiesManager propManager = new PropertiesManager();
		//ExcelFileManager fileManager = new ExcelFileManager(propManager);
		//ArrayList<String> excelColumns = fileManager.getColumns(false);
		
		MySQLDAOManager mysqlManager = new MySQLDAOManager(propManager);
		ArrayList<String> mysqlColumns = mysqlManager.getColumns();
		
		
	}
	
}
