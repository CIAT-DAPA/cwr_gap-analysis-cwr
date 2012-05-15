package io;

import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;

import org.apache.poi.openxml4j.exceptions.InvalidFormatException;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.ss.usermodel.Sheet;
import org.apache.poi.ss.usermodel.Workbook;
import org.apache.poi.ss.usermodel.WorkbookFactory;

import config.PropertiesManager;

public class ExcelFileManager {

	private Workbook workBook;

	public ExcelFileManager(PropertiesManager propManager) throws IOException,
			InvalidFormatException {
		InputStream inp = new FileInputStream(
				propManager.getProperty("excel.filename"));
		this.workBook = WorkbookFactory.create(inp);
	}

	public ArrayList<String> getColumns(boolean ignoreExtraColumns) {

		// get first sheet.
		Sheet sheet = workBook.getSheetAt(0);
		// get first row which must have all the column names.
		Row row = sheet.getRow(sheet.getFirstRowNum());
		int rowNumber = row.getLastCellNum();
		ArrayList<String> columns = new ArrayList<>();
		// add all column names to an array.
		for (int c = 0; c < rowNumber; c++) {
			columns.add(row.getCell(c).getStringCellValue());
			System.out.println(columns.get(c));
		}

		return columns;
	}

}
