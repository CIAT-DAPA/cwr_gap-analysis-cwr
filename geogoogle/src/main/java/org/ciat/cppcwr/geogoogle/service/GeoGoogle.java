/**
 * Copyright 2012 International Center for Tropical Agriculture (CIAT).
 * 
 * This file is part of: 
 * "GeoGoogle - Collecting Protecting and Preparing Crop Wild Relatives"
 * 
 * GeoGoogle is free software: You can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * at your option) any later version.
 * 
 * GeoGoogle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * See <http://www.gnu.org/licenses/>.
 */
package org.ciat.cppcwr.geogoogle.service;

import org.ciat.cppcwr.geogoogle.config.GeoGoogleModule;
import org.ciat.cppcwr.geogoogle.utils.UrlSignerGenerator;

import com.google.inject.Guice;
import com.google.inject.Injector;

public class GeoGoogle {

	public void init() {}
	
	public static void main(String[] args) {
		GeoGoogle geo = new GeoGoogle();
		geo.init();
		
		// TESTS
		Injector inject = Guice.createInjector(new GeoGoogleModule());
		UrlSignerGenerator usg = inject.getInstance(UrlSignerGenerator.class);
		//UrlSignerGenerator usg = new UrlSignerGenerator();
		System.out.println(usg.testPM());
		
	}
	
}
