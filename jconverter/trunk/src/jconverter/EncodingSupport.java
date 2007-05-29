/**
 * ===========================================
 * JFreeReport : a free Java reporting library
 * ===========================================
 *
 * Project Info:  http://reporting.pentaho.org/
 *
 * (C) Copyright 2001-2007, by Object Refinery Ltd, Pentaho Corporation and Contributors.
 *
 * This library is free software; you can redistribute it and/or modify it under the terms
 * of the GNU Lesser General Public License as published by the Free Software Foundation;
 * either version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along with this
 * library; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330,
 * Boston, MA 02111-1307, USA.
 *
 * [Java is a trademark or registered trademark of Sun Microsystems, Inc.
 * in the United States and other countries.]
 *
 * ------------
 * EncodingSupport.java
 * ------------
 * (C) Copyright 2001-2007, by Object Refinery Ltd, Pentaho Corporation and Contributors.
 */
//package org.jfree.report.util;
package jconverter;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;

//import org.jfree.report.JFreeReportBoot;
//import org.jfree.util.Log;

/**
 * A global registry for all supported encodings.
 *
 * @author Thomas Morgner.
 */
public final class EncodingSupport
{
  /**
   * Default Constructor.
   */
  private EncodingSupport ()
  {
  }

  /**
   * Storage for the known encodings.
   */
  private static HashMap knownEncodings;

  /**
   * the string that should be encoded.
   */
  private static final String TEST_STRING = " ";

  /**
   * Returns <code>true</code> if the encoding is valid, and <code>false</code>
   * otherwise.
   *
   * @param encoding the encoding (name).
   * @return A boolean.
   */
  public static boolean isSupportedEncoding (final String encoding)
  {
    if (encoding == null)
    {
      throw new NullPointerException();
    }
    if (knownEncodings == null)
    {
      knownEncodings = new HashMap();
    }

    final Boolean value = (Boolean) knownEncodings.get(encoding);
    if (value != null)
    {
      return value.booleanValue();
    }

    try
    {
      TEST_STRING.getBytes(encoding);
      knownEncodings.put(encoding, Boolean.TRUE);
      return true;
    }
    catch (UnsupportedEncodingException ue)
    {
      knownEncodings.put(encoding, Boolean.FALSE);
      //Log.info(new Log.SimpleMessage("Encoding ", encoding, " is not supported."));
      return false;
    }
    catch(Exception e)
    {
      knownEncodings.put(encoding, Boolean.FALSE);
      //Log.info(new Log.SimpleMessage("Encoding ", encoding, " is buggy. Blame Sun for it."));
      return false;
    }
  }


  /**
   * Helper method to read the platform default encoding from the VM's system properties.
   *
   * @return the contents of the system property "file.encoding".
   */
/*  public static String getPlatformDefaultEncoding ()
  {
    return JFreeReportBoot.getInstance().getGlobalConfig().getConfigProperty
            ("file.encoding", "Cp1252");
  }*/

}
