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
package org.ciat.cppcwr.geogoogle.utils;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.URISyntaxException;
import java.net.URL;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;

import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;

import org.ciat.cppcwr.geogoogle.config.GeoGoogleModule;
import org.ciat.cppcwr.geogoogle.service.manage.PropertiesManager;

import com.google.inject.Guice;
import com.google.inject.Inject;
import com.google.inject.Injector;

/**
 * @author Héctor Tobón (htobon)
 */
public class UrlSignerGenerator {

	@Inject
	private PropertiesManager pm;
	
	private String keyString;
	private String clientID;

	// This variable stores the binary key, which is computed from the string
	// (util.Base64) key
	private byte[] key;
/*
	public static void main(String[] args) throws IOException,
			InvalidKeyException, NoSuchAlgorithmException, URISyntaxException {

		// Convert the string to a URL so we can parse it
		URL url = new URL("https://maps.googleapis.com/maps/api/geocode/xml?sensor=false&address=Colombia,+Valle+del+Cauca,+Cali,+Universidad+Icesi");
		//URL url = new URL("https://maps.googleapis.com/api/geocode/json?sensor=false&"+parameters);
		UrlSignerGenerator signer = new UrlSignerGenerator();
		String request = signer.signRequest(url.getPath(), url.getQuery());

		System.out.println("Signed URL :" + url.getProtocol() + "://"
				+ url.getHost() + request);
	}
	*/
	public UrlSignerGenerator() throws IOException {		
		// Convert the key from 'web safe' base 64 to binary
		// TESTS
		Injector inject = Guice.createInjector(new GeoGoogleModule());
		pm = inject.getInstance(PropertiesManager.class);// New
		keyString = pm.getProperty("google.key"); // pm null, how get instance?
		/*
		 * Injector inject = Guice.createInjector(new GeoGoogleModule()); so, I need to create a PropertiesManagerModule in config package and same way <-
		 * UrlSignerGenerator usg = inject.getInstance(UrlSignerGenerator.class);
		 * */
		keyString = keyString.replace('-', '+');
		keyString = keyString.replace('_', '/');
		System.out.println("Key: " + keyString);
	    this.key = Base64.decode(keyString);
		this.clientID = pm.getProperty("google.client");
	}

	/**
	 * Create a string URL with the corresponding signature code and client id.
	 * @param path
	 * @param query
	 * @return the specified String URL
	 * @throws NoSuchAlgorithmException
	 * @throws InvalidKeyException
	 * @throws UnsupportedEncodingException
	 * @throws URISyntaxException
	 */
	public String signRequest(String path, String query)
			throws NoSuchAlgorithmException, InvalidKeyException,
			UnsupportedEncodingException, URISyntaxException {		
		
		// Retrieve the proper URL components to sign
		String resource = path + '?' + query + "&client="+this.clientID;

		// Get an HMAC-SHA1 signing key from the raw key bytes
		SecretKeySpec sha1Key = new SecretKeySpec(key, "HmacSHA1");

		// Get an HMAC-SHA1 Mac instance and initialize it with the HMAC-SHA1
		// key
		Mac mac = Mac.getInstance("HmacSHA1");
		mac.init(sha1Key);

		// compute the binary signature for the request
		byte[] sigBytes = mac.doFinal(resource.getBytes());

		// base 64 encode the binary signature
		String signature = Base64.encodeBytes(sigBytes);

		// convert the signature to 'web safe' base 64
		signature = signature.replace('+', '-');
		signature = signature.replace('/', '_');

		return resource + "&signature=" + signature;
	}
	
	public String testPM() {
		return "Client ID: "+pm.getProperty("google.client");
	}
}
