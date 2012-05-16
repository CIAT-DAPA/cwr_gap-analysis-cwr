package upload;

import io.ExcelFileManager;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.Map;

import org.apache.poi.hssf.usermodel.HSSFWorkbook;
import org.apache.poi.hssf.usermodel.examples.RepeatingRowsAndColumns;
import org.apache.poi.openxml4j.exceptions.InvalidFormatException;
import org.apache.poi.ss.usermodel.Cell;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.ss.usermodel.Sheet;
import org.apache.poi.ss.usermodel.Workbook;

import config.PropertiesManager;
import dao.MySQLDAOManager;

public class Excel {

	private PropertiesManager propManager;
	private ExcelFileManager fileManager;
	private MySQLDAOManager daoManager;

	public Excel() throws InvalidFormatException, IOException,
			ClassNotFoundException, SQLException {
		this.propManager = new PropertiesManager();
		this.fileManager = new ExcelFileManager(propManager);
		this.daoManager = new MySQLDAOManager(propManager);
	}

	public String generateQuery(Map<String, Cell> columnValues) {
		StringBuilder query = new StringBuilder();
		query.append("INSERT INTO raw_occurrences_temp (");

		boolean isFirstColumn = true;
		for (String column : columnValues.keySet()) {
			if (isFirstColumn) {
				isFirstColumn = false;
			} else {
				query.append(", ");
			}
			query.append(column.equals("id") ? "old_id" : column);
		}
		query.append(") VALUES (");

		Cell cell = null;
		isFirstColumn = true;
		for (String column : columnValues.keySet()) {
			if (isFirstColumn) {
				isFirstColumn = false;
			} else {
				query.append(", ");
			}
			cell = columnValues.get(column);
			if (cell.getCellType() == Cell.CELL_TYPE_STRING) {
				query.append("'" + cell + "'");
			} else {
				if (("" + cell.toString()).endsWith(".0")) {
					query.append((int) cell.getNumericCellValue());
				} else {
					query.append(cell.toString());
				}
			}
		}
		query.append(")");

		return query.toString();
	}

	public Map<String, Cell> identifyColumnsWithValues(Sheet sheet,
			int rowNumber) {
		// getting excel columns.
		ArrayList<String> columns = fileManager.getColumns(true);

		Row row = sheet.getRow(rowNumber);
		Map<String, Cell> columnValues = new LinkedHashMap<String, Cell>();
		Cell cell;
		for (int colNum = 0; colNum < columns.size(); colNum++) {
			cell = row.getCell(colNum);
			if (cell != null) {
				columnValues.put(columns.get(colNum), cell);
			}
		}
		return columnValues;

	}

	public ExcelFileManager getFileManager() {
		return this.fileManager;
	}

	public static void main(String[] args) throws InvalidFormatException,
			ClassNotFoundException, IOException, SQLException {

		Excel excelUpload = new Excel();

		Workbook workbook = excelUpload.getFileManager().getWorkBook();
		Sheet sheet = workbook.getSheetAt(0);

		Map<String, Cell> columnValues;
		Row row;
		String query;
		for (int rowNum = 1; rowNum < sheet.getLastRowNum(); rowNum++) {
			row = sheet.getRow(rowNum);
			columnValues = excelUpload.identifyColumnsWithValues(sheet, 2);
			query = excelUpload.generateQuery(columnValues);
			System.out.println(rowNum + " - " + query);
		}

	}

}
