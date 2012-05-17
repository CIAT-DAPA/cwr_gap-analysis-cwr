package upload;

import io.ExcelFileManager;

import java.io.IOException;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.Map;

import org.apache.poi.hssf.model.HSSFFormulaParser;
import org.apache.poi.hssf.usermodel.HSSFCellStyle;
import org.apache.poi.hssf.usermodel.HSSFDateUtil;
import org.apache.poi.hssf.usermodel.HSSFFormulaEvaluator;
import org.apache.poi.hssf.util.HSSFCellUtil;
import org.apache.poi.openxml4j.exceptions.InvalidFormatException;
import org.apache.poi.ss.usermodel.Cell;
import org.apache.poi.ss.usermodel.FormulaEvaluator;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.ss.usermodel.Sheet;
import org.apache.poi.ss.usermodel.Workbook;

import com.mysql.jdbc.StringUtils;

import validate.ExcelValidator;

import config.PropertiesManager;
import dao.MySQLDAOManager;

public class Excel {

	private PropertiesManager propManager;
	private ExcelFileManager fileManager;
	private MySQLDAOManager daoManager;

	public MySQLDAOManager getDaoManager() {
		return this.daoManager;
	}

	public Excel() throws InvalidFormatException, IOException,
			ClassNotFoundException, SQLException {
		this.propManager = new PropertiesManager();
		this.fileManager = new ExcelFileManager(propManager);
		this.daoManager = new MySQLDAOManager(propManager);
	}

	public String generateQuery(Map<String, Cell> columnValues) {
		StringBuilder query = new StringBuilder();
		query.append("INSERT INTO raw_occurrences (");

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
		FormulaEvaluator evaluator = fileManager.getWorkBook().getCreationHelper().createFormulaEvaluator();
		for (String column : columnValues.keySet()) {
			if (isFirstColumn) {
				isFirstColumn = false;
			} else {
				query.append(", ");
			}
			cell = columnValues.get(column);
			if(cell.getCellType() == Cell.CELL_TYPE_NUMERIC) {
				String tmp1 = evaluator.evaluate(cell).toString();
				if (("" + cell.toString()).endsWith(".0")) {
					query.append((int) cell.getNumericCellValue());
				} else if (HSSFDateUtil.isCellDateFormatted(cell)) {
					query.append("'"+cell.toString()+"'");
				} else {
					query.append(cell.toString());
				}				
			} else if(cell.getCellType() == Cell.CELL_TYPE_FORMULA){
				if(evaluator.evaluateFormulaCell(cell) == Cell.CELL_TYPE_NUMERIC) {
					query.append(cell.getNumericCellValue());
				} else if(evaluator.evaluateFormulaCell(cell) == Cell.CELL_TYPE_STRING) {
					query.append("'" + cell.getStringCellValue().replace("'", "\\'") + "'");
				}
			} else {
				query.append("'" + cell.getStringCellValue().replace("'", "\\'") + "'");				
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
		for (int colNum = 0; colNum < columns.size(); colNum++) {
			Cell cell = row.getCell(colNum);
			if (cell != null && !cell.toString().equals("")) {
				// System.out.println(cell);
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

		ExcelValidator validator = new ExcelValidator();
		if (validator.validate()) {

			Excel excelUpload = new Excel();

			Workbook workbook = excelUpload.getFileManager().getWorkBook();
			Sheet sheet = workbook.getSheetAt(0);

			Map<String, Cell> columnValues;
			Row row;
			String query;
			int num = 0;
			for (int rowNum = 1; rowNum <= sheet.getLastRowNum(); rowNum++) {
				row = sheet.getRow(rowNum);
				columnValues = excelUpload.identifyColumnsWithValues(sheet,
						rowNum);
				query = excelUpload.generateQuery(columnValues);
				System.out.println(rowNum + " - " + query);
				num += excelUpload.getDaoManager().insertQuery(query);

			}

			System.out.println("\nTOTAL inserted values: " + num);
		}

	}

}
