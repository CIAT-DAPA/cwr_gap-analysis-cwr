package validate;

import java.io.IOException;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Iterator;

import org.apache.poi.openxml4j.exceptions.InvalidFormatException;

import config.PropertiesManager;
import dao.MySQLDAOManager;
import io.ExcelFileManager;

public class ExcelValidator {

	private PropertiesManager propManager;
	private ExcelFileManager fileManager;
	private MySQLDAOManager daoManager;

	public ExcelValidator() throws InvalidFormatException, IOException,
			ClassNotFoundException, SQLException {
		this.propManager = new PropertiesManager();
		System.out.println("Reading EXCEL file.....");
		this.fileManager = new ExcelFileManager(propManager);
		System.out.println("Reading MYSQL Database.....");
		this.daoManager = new MySQLDAOManager(propManager);
	}

	public boolean validate() throws SQLException {		
		ArrayList<String> excelColumns = fileManager.getColumns(true);
		ArrayList<String> mysqlColumns = daoManager.getColumns();

		System.out.println("\n----- Start validation process -----");
		// validation process.
		boolean isValid = true;
		String excelColumnName;
		for (int c = 0; c < excelColumns.size(); c++) {
			excelColumnName = excelColumns.get(c);
			if (mysqlColumns.contains(excelColumnName.toLowerCase())) {
				System.out.println(excelColumnName + " --> valid");
				mysqlColumns.remove(mysqlColumns.indexOf(excelColumnName.toLowerCase()));
			} else {
				isValid = false;
				System.out.println(">> Problem whit column " + excelColumnName);
			}
		}

		if (!isValid) {
			System.out.println("\nThe file is not valid!");
			return false;
		} else {
			System.out.println("\nThe file is valid");
			return true;
		}
	}

	public static void main(String[] args) throws InvalidFormatException,
			IOException, ClassNotFoundException, SQLException {

		ExcelValidator validator = new ExcelValidator();
		validator.validate();

	}

}
