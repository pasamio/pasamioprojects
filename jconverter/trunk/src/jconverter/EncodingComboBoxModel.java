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
 * EncodingComboBoxModel.java
 * ------------
 * (C) Copyright 2001-2007, by Object Refinery Ltd, Pentaho Corporation and Contributors.
 */
//package org.jfree.report.modules.gui.base.components;
package jconverter;

import java.io.BufferedInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.Enumeration;
import java.util.Properties;
import javax.swing.ComboBoxModel;
import javax.swing.event.ListDataEvent;
import javax.swing.event.ListDataListener;

//import org.jfree.report.JFreeReportBoot;
//import org.jfree.report.util.EncodingSupport;
//import org.jfree.util.Log;
//import org.jfree.util.ObjectUtilities;

/**
 * A model for the 'encoding' combo box. This combobox model presents a selection for all
 * available string encodings.
 *
 * @author Thomas Morgner.
 */
public class EncodingComboBoxModel implements ComboBoxModel
{
  /**
   * A default description.
   */
  private static final String ENCODING_DEFAULT_DESCRIPTION =
          "[no description]";

  /**
   * The property that defines which encodings are available in the export dialogs.
   */
  public static final String AVAILABLE_ENCODINGS
          = "org.jfree.report.modules.gui.base.EncodingsAvailable";

  /**
   * The encodings available properties value for all properties.
   */
  public static final String AVAILABLE_ENCODINGS_ALL = "all";
  /**
   * The encodings available properties value for properties defined in the properties
   * file.
   */
  public static final String AVAILABLE_ENCODINGS_FILE = "file";
  /**
   * The encodings available properties value for no properties defined. The encoding
   * selection will be disabled.
   */
  public static final String AVAILABLE_ENCODINGS_NONE = "none";

  /**
   * The name of the properties file used to define the available encodings. The property
   * points to a resources in the classpath, not to a real file!
   */
  public static final String ENCODINGS_DEFINITION_FILE
          = "org.jfree.report.modules.gui.base.EncodingsFile";

  /**
   * The default name for the encoding properties file. This property defaults to
   * &quot;/org/jfree/report/jfreereport-encodings.properties&quot;.
   */
  public static final String ENCODINGS_DEFINITION_FILE_DEFAULT
          = "org/jfree/report/modules/gui/base/components/jfreereport-encodings.properties";


  /**
   * An encoding comparator.
   */
  private static class EncodingCarrierComparator implements Comparator
  {
    public EncodingCarrierComparator ()
    {
    }

    /**
     * Compares its two arguments for order.  Returns a negative integer, zero, or a
     * positive integer as the first argument is less than, equal to, or greater than the
     * second.
     *
     * @param o1 the first object to be compared.
     * @param o2 the second object to be compared.
     * @return a negative integer, zero, or a positive integer as the first argument is
     *         less than, equal to, or greater than the second.
     *
     * @throws java.lang.ClassCastException if the arguments' types prevent them from
     *                                      being compared by this Comparator.
     */
    public int compare (final Object o1, final Object o2)
    {
      final EncodingCarrier e1 = (EncodingCarrier) o1;
      final EncodingCarrier e2 = (EncodingCarrier) o2;
      return e1.getName().toLowerCase().compareTo(e2.getName().toLowerCase());
    }

    /**
     * Returns <code>true</code> if this object is equal to <code>o</code>, and
     * <code>false</code> otherwise.
     *
     * @param o the object.
     * @return A boolean.
     */
    public boolean equals (final Object o)
    {
      if (o == null)
      {
        return false;
      }
      return getClass().equals(o.getClass());
    }

    /**
     * All comparators of this type are equal.
     *
     * @return A hash code.
     */
    public int hashCode ()
    {
      return getClass().hashCode();
    }
  }

  /**
   * An encoding carrier.
   */
  private static class EncodingCarrier
  {
    /**
     * The encoding name.
     */
    private String name;

    /**
     * The encoding description.
     */
    private String description;

    /**
     * The display name.
     */
    private String displayName;

    /**
     * Creates a new encoding.
     *
     * @param name        the name (<code>null</code> not permitted).
     * @param description the description.
     */
    public EncodingCarrier (final String name, final String description)
    {
      if (name == null)
      {
        throw new NullPointerException();
      }
      this.name = name;
      this.description = description;
      final StringBuffer dName = new StringBuffer();
      dName.append(name);
      dName.append(" (");
      dName.append(description);
      dName.append(")");
      this.displayName = dName.toString();
    }

    /**
     * Returns the name.
     *
     * @return The name.
     */
    public String getName ()
    {
      return name;
    }

    /**
     * Returns the description.
     *
     * @return The description.
     */
    public String getDescription ()
    {
      return description;
    }

    /**
     * Returns the display name (the regular name followed by the description in
     * brackets).
     *
     * @return The display name.
     */
    public String getDisplayName ()
    {
      return displayName;
    }

    /**
     * Returns <code>true</code> if the objects are equal, and <code>false</code>
     * otherwise.
     *
     * @param o the object.
     * @return A boolean.
     */
    public boolean equals (final Object o)
    {
      if (this == o)
      {
        return true;
      }
      if (!(o instanceof EncodingCarrier))
      {
        return false;
      }

      final EncodingCarrier carrier = (EncodingCarrier) o;

      if (!name.equalsIgnoreCase(carrier.name))
      {
        return false;
      }

      return true;
    }

    /**
     * Returns a hash code.
     *
     * @return The hash code.
     */
    public int hashCode ()
    {
      return name.hashCode();
    }
  }

  /**
   * Contains the known default encodings.
   */
  private static Properties defaultEncodings;

  /**
   * Storage for the encodings.
   */
  private final ArrayList encodings;

  /**
   * Storage for registered listeners.
   */
  private ArrayList listDataListeners;

  /**
   * The selected index.
   */
  private int selectedIndex;

  /**
   * The selected object.
   */
  private Object selectedObject;

  /**
   * Creates a new model.
   */
  public EncodingComboBoxModel ()
  {
    encodings = new ArrayList();
    listDataListeners = null;
    selectedIndex = -1;
  }

  /**
   * Adds an encoding.
   *
   * @param name        the name.
   * @param description the description.
   * @return <code>true</code> if the encoding is valid and added to the model,
   *         <code>false</code> otherwise.
   */
  public boolean addEncoding (final String name, final String description)
  {
    if (EncodingSupport.isSupportedEncoding(name))
    {
      encodings.add(new EncodingCarrier(name, description));
    }
    else
    {
      return false;
    }

    fireContentsChanged();
    return true;
  }

  /**
   * Adds an encoding to the model without checking its validity.
   *
   * @param name        the name.
   * @param description the description.
   */
  public void addEncodingUnchecked (final String name, final String description)
  {
    encodings.add(new EncodingCarrier(name, description));
    fireContentsChanged();
  }

  public void removeEncoding (final String name)
  {
    if (encodings.remove(name))
    {
      fireContentsChanged();
    }
  }

  /**
   * Make sure, that this encoding is defined and selectable in the combobox model.
   *
   * @param encoding the encoding that should be verified.
   */
  public void ensureEncodingAvailable (final String encoding)
  {
    if (encoding == null)
    {
      throw new NullPointerException("Encoding must not be null");
    }
    final String desc = getDefaultEncodings().getProperty(encoding, ENCODING_DEFAULT_DESCRIPTION);
    final EncodingCarrier ec = new EncodingCarrier(encoding, desc);
    if (encodings.contains(ec) == false)
    {
      encodings.add(ec);
      fireContentsChanged();
    }
  }

  /**
   * Sorts the encodings. Keep the selected object ...
   */
  public void sort ()
  {
    final Object selectedObject = getSelectedItem();
    Collections.sort(encodings, new EncodingCarrierComparator());
    setSelectedItem(selectedObject);
    fireContentsChanged();
  }

  /**
   * Notifies all registered listeners that the content of the model has changed.
   */
  protected void fireContentsChanged ()
  {
    if (listDataListeners == null)
    {
      return;
    }
    final ListDataEvent evt = new ListDataEvent(this, ListDataEvent.CONTENTS_CHANGED, 0, getSize());
    for (int i = 0; i < listDataListeners.size(); i++)
    {
      final ListDataListener l = (ListDataListener) listDataListeners.get(i);
      l.contentsChanged(evt);
    }
  }

  /**
   * Set the selected item. The implementation of this  method should notify all
   * registered <code>ListDataListener</code>s that the contents have changed.
   *
   * @param anItem the list object to select or <code>null</code> to clear the selection
   */
  public void setSelectedItem (final Object anItem)
  {
    selectedObject = anItem;
    if (anItem instanceof String)
    {
      final int size = getSize();
      for (int i = 0; i < size; i++)
      {
        if (anItem.equals(getElementAt(i)))
        {
          selectedIndex = i;
          fireContentsChanged(-1, -1);
          return;
        }
      }
    }
    selectedIndex = -1;
    fireContentsChanged(-1, -1);
  }

  /**
   * Returns the selected index.
   *
   * @return The index.
   */
  public int getSelectedIndex ()
  {
    return selectedIndex;
  }

  /**
   * Defines the selected index for this encoding model.
   *
   * @param index the selected index or -1 to clear the selection.
   * @throws java.lang.IllegalArgumentException
   *          if the given index is invalid.
   */
  public void setSelectedIndex (final int index)
  {
    if (index == -1)
    {
      selectedIndex = -1;
      selectedObject = null;
      return;
    }
    if (index < -1 || index >= getSize())
    {
      throw new IllegalArgumentException("Index is invalid.");
    }
    selectedIndex = index;
    selectedObject = getElementAt(index);
    fireContentsChanged(-1, -1);
  }

  /**
   * Returns the selected encoding.
   *
   * @return The encoding (name).
   */
  public String getSelectedEncoding ()
  {
    if (selectedIndex == -1)
    {
      return null;
    }
    final EncodingCarrier ec = (EncodingCarrier) encodings.get(selectedIndex);
    return ec.getName();
  }

  /**
   * Returns the selected item.
   *
   * @return The selected item or <code>null</code> if there is no selection
   */
  public Object getSelectedItem ()
  {
    return selectedObject;
  }

  /**
   * Returns the length of the list.
   *
   * @return the length of the list
   */
  public int getSize ()
  {
    return encodings.size();
  }

  /**
   * Returns the value at the specified index.
   *
   * @param index the requested index
   * @return the value at <code>index</code>
   */
  public Object getElementAt (final int index)
  {
    final EncodingCarrier ec = (EncodingCarrier) encodings.get(index);
    return ec.getDisplayName();
  }

  /**
   * Adds a listener to the list that's notified each time a change to the data model
   * occurs.
   *
   * @param l the <code>ListDataListener</code> to be added
   */
  public void addListDataListener (final ListDataListener l)
  {
    if (listDataListeners == null)
    {
      listDataListeners = new ArrayList(5);
    }
    listDataListeners.add(l);
  }

  /**
   * Removes a listener from the list that's notified each time a change to the data model
   * occurs.
   *
   * @param l the <code>ListDataListener</code> to be removed
   */
  public void removeListDataListener (final ListDataListener l)
  {
    if (listDataListeners == null)
    {
      return;
    }
    listDataListeners.remove(l);
  }

  /**
   * Adds the basic encodings from the international JDK to the default encoding names
   * collection.
   */
  private static void addBasicEncodings ()
  {
    // basic encoding set, base encodings
    defaultEncodings.put("ASCII", "American Standard Code for Information Interchange");
    defaultEncodings.put("Cp1252", "Windows Latin-1");
    defaultEncodings.put("ISO-8859-1", "Latin alphabet No. 1");
    defaultEncodings.put("ISO-8859-15", "Latin alphabet No. 9, 'Euro' enabled");
    defaultEncodings.put("UTF-8", "8 Bit UCS Transformation Format");
    defaultEncodings.put("UTF-16", "16 Bit UCS Transformation Format");
    // missing: UTF-16BE, UTF-16LE are no base need in EndUser environments

    //extended encoding set, contained in lib/charsets.jar
    defaultEncodings.put("Cp1250", "Windows Eastern Europe");
    defaultEncodings.put("Cp1251", "Windows Russian (Cyrillic)");
    defaultEncodings.put("Cp1253", "Windows Greek");
    defaultEncodings.put("Cp1254", "Windows Turkish");
    defaultEncodings.put("Cp1255", "Windows Hebrew");
    defaultEncodings.put("Cp1256", "Windows Arabic");
    defaultEncodings.put("Cp1257", "Windows Baltic");
    defaultEncodings.put("Cp1258", "Windows Vietnamese");
    defaultEncodings.put("ISO-8859-2", "Latin alphabet No. 2");
    defaultEncodings.put("ISO-8859-3", "Latin alphabet No. 3");
    defaultEncodings.put("ISO-8859-4", "Latin alphabet No. 4");
    defaultEncodings.put("ISO-8859-5", "Latin/Cyrillic Alphabet");
    defaultEncodings.put("ISO-8859-6", "Latin/Arabic Alphabet");
    defaultEncodings.put("ISO-8859-7", "Latin/Greek Alphabet");
    defaultEncodings.put("ISO-8859-8", "Latin/Hebrew Alphabet");
    defaultEncodings.put("ISO-8859-9", "Latin alphabet No. 5");
    defaultEncodings.put("ISO-8859-13", "Latin alphabet No. 7");
    defaultEncodings.put("MS932", "Windows Japanese");
    defaultEncodings.put("EUC-JP", "JISX 0201, 0208 and 0212, EUC encoding Japanese");
    defaultEncodings.put("EUC-JP-LINUX", "JISX 0201, 0208, EUC encoding Japanese");
    defaultEncodings.put("SJIS", "Shift-JIS, Japanese");
    defaultEncodings.put("ISO-2022-JP", "JIS X 0201, 0208, in ISO 2002 form, Japanese");
    defaultEncodings.put("MS936", "Windows Simplified Chinese");
    defaultEncodings.put("GB18030", "Simplified Chinese, PRC standard");
    defaultEncodings.put("EUC_CN", "GB2312, EUC encoding, Simplified Chinese");
    defaultEncodings.put("GB2312", "GB2312, EUC encoding, Simplified Chinese");
    defaultEncodings.put("GBK", "GBK, Simplified Chinese");
    defaultEncodings.put("ISCII91", "ISCII encoding of Indic scripts");
    defaultEncodings.put("ISO-2022-CN-GB", "GB2312 in ISO 2022 CN form, Simplified Chinese");
    defaultEncodings.put("MS949", "Windows Korean");
    defaultEncodings.put("EUC_KR", "KS C 5601, EUC encoding, Korean");
    defaultEncodings.put("ISO-2022-KR", "ISO 2022 KR, Korean");
    defaultEncodings.put("MS950", "Windows Traditional Chinese");
    defaultEncodings.put("EUC-TW", "CNS 11643 (Plane 1-3), EUC encoding, Traditional Chinese");
    defaultEncodings.put("ISO-2022-CN-CNS",
            "CNS 11643 in ISO 2022 CN form, Traditional Chinese");
    defaultEncodings.put("Big5", "Big5, Traditional Chinese");
    defaultEncodings.put("Big5-HKSCS", "Big5 with Hong Kong extensions, Traditional Chinese");
    defaultEncodings.put("TIS-620", "TIS 620, Thai");
    defaultEncodings.put("KOI8-R", "KOI8-R, Russian");
  }

  /**
   * Adds the extended encodings from the international JDK to the default encoding names
   * collection.
   */
  private static void addExtendedEncodings ()
  {
    //extended encoding set, contained in lib/charsets.jar
    // international JDK only ...
    defaultEncodings.put("Big5_Solaris",
            "Big5 with seven additional Hanzi ideograph character mappings");
    defaultEncodings.put("Cp037",
            "USA, Canada (Bilingual, French), Netherlands, Portugal, Brazil, Australia");
    defaultEncodings.put("Cp273", "IBM Austria, Germany");
    defaultEncodings.put("Cp277", "IBM Denmark, Norway");
    defaultEncodings.put("Cp278", "IBM Finland, Sweden");
    defaultEncodings.put("Cp280", "IBM Italy");
    defaultEncodings.put("Cp284", "IBM Catalan/Spain, Spanish Latin America");
    defaultEncodings.put("Cp285", "IBM United Kingdom, Ireland");
    defaultEncodings.put("Cp297", "IBM France");
    defaultEncodings.put("Cp420", "IBM Arabic");
    defaultEncodings.put("Cp424", "IBM Hebrew");
    defaultEncodings.put("Cp437", "MS-DOS United States, Australia, New Zealand, South Africa");
    defaultEncodings.put("Cp500", "EBCDIC 500V1");
    defaultEncodings.put("Cp737", "PC Greek");
    defaultEncodings.put("Cp775", "PC Baltik");
    defaultEncodings.put("Cp838", "IBM Thailand extended SBCS");
    defaultEncodings.put("Cp850", "MS-DOS Latin-1");
    defaultEncodings.put("Cp852", "MS-DOS Latin 2");
    defaultEncodings.put("Cp855", "IBM Cyrillic");
    defaultEncodings.put("Cp856", "IBM Hebrew");
    defaultEncodings.put("Cp857", "IBM Turkish");
    defaultEncodings.put("Cp858", "MS-DOS Latin-1 with Euro character");
    defaultEncodings.put("Cp860", "MS-DOS Portuguese");
    defaultEncodings.put("Cp861", "MS-DOS Icelandic");
    defaultEncodings.put("Cp862", "PC Hebrew");
    defaultEncodings.put("Cp863", "MS-DOS Canadian French");
    defaultEncodings.put("Cp864", "PC Arabic");
    defaultEncodings.put("Cp865", "MS-DOS Nordic");
    defaultEncodings.put("Cp866", "MS-DOS Russian");
    defaultEncodings.put("Cp868", "MS-DOS Pakistan");
    defaultEncodings.put("Cp869", "IBM Modern Greek");
    defaultEncodings.put("Cp870", "IBM Multilingual Latin-2");
    defaultEncodings.put("Cp871", "IBM Iceland");
    defaultEncodings.put("Cp874", "IBM Thai");
    defaultEncodings.put("Cp875", "IBM Greek");
    defaultEncodings.put("Cp918", "IBM Pakistan (Urdu)");
    defaultEncodings.put("Cp921", "IBM Lativa, Lithuania (AIX, DOS)");
    defaultEncodings.put("Cp922", "IBM Estonia (AIX, DOS)");
    defaultEncodings.put("Cp930",
            "Japanese Katakana-Kanji mixed with 4370 UDC, superset of 5026");
    defaultEncodings.put("Cp933", "Korean mixed with 1880 UDC, superset of 5029");
    defaultEncodings.put("Cp935", "Simplified Chinese mixed with 1880 UDC, superset of 5031");
    defaultEncodings.put("Cp937",
            "Traditional Chinsese Hostmixed with 6204 UDC, superset of 5033");
    defaultEncodings.put("Cp939", "Japanese Latin Kanji mixed with 4370 UDC, superset of 5035");
    defaultEncodings.put("Cp942", "IBM OS/2 Japanese, superset of Cp932");
    defaultEncodings.put("Cp942C", "Variant of Cp942: IBM OS/2 Japanese, superset of Cp932");
    defaultEncodings.put("Cp943", "IBM OS/2 Japanese, superset of Cp932 and Shift-JIS");
    defaultEncodings.put("Cp943C",
            "Variant of Cp943: IBM OS/2 Japanese, superset of Cp932 and Shift-JIS");
    defaultEncodings.put("Cp948", "IBM OS/2 Chinese (Taiwan) superset of Cp938");
    defaultEncodings.put("Cp949", "PC Korean");
    defaultEncodings.put("Cp949C", "Variant of Cp949: PC Korean");
    defaultEncodings.put("Cp950", "PC Chinese (Hong Kong, Taiwan)");
    defaultEncodings.put("Cp964", "AIX Chinese (Taiwan)");
    defaultEncodings.put("Cp970", "AIX Korean");
    defaultEncodings.put("Cp1006", "IBM AIX Parkistan (Urdu)");
    defaultEncodings.put("Cp1025",
            "IBM Multilingual Cyrillic: Bulgaria, Bosnia, Herzegovinia, Macedonia (FYR)");
    defaultEncodings.put("Cp1026", "IBM Latin-5, Turkey");
    defaultEncodings.put("Cp1046", "IBM Arabic Windows");
    defaultEncodings.put("Cp1097", "IBM Iran (Farsi)/Persian");
    defaultEncodings.put("Cp1098", "IBM Iran (Farsi)/Persian (PC)");
    defaultEncodings.put("Cp1112", "IBM Lativa, Lithuania");
    defaultEncodings.put("Cp1122", "IBM Estonia");
    defaultEncodings.put("Cp1123", "IBM Ukraine");
    defaultEncodings.put("Cp1124", "IBM AIX Ukraine");
    defaultEncodings.put("Cp1140",
            "USA, Canada (Bilingual, French), Netherlands, Portugal, Brazil, Australia (with Euro)");
    defaultEncodings.put("Cp1141", "IBM Austria, Germany (Euro enabled)");
    defaultEncodings.put("Cp1142", "IBM Denmark, Norway (Euro enabled)");
    defaultEncodings.put("Cp1143", "IBM Finland, Sweden (Euro enabled)");
    defaultEncodings.put("Cp1144", "IBM Italy (Euro enabled)");
    defaultEncodings.put("Cp1145", "IBM Catalan/Spain, Spanish Latin America (with Euro)");
    defaultEncodings.put("Cp1146", "IBM United Kingdom, Ireland (with Euro)");
    defaultEncodings.put("Cp1147", "IBM France (with Euro)");
    defaultEncodings.put("Cp1148", "IBM EBCDIC 500V1 (with Euro)");
    defaultEncodings.put("Cp1149", "IBM Iceland (with Euro)");
    defaultEncodings.put("Cp1381", "IBM OS/2, DOS People's Republic of Chine (PRC)");
    defaultEncodings.put("Cp1383", "IBM AIX People's Republic of Chine (PRC)");
    defaultEncodings.put("Cp33722", "IBM-eucJP - Japanese (superset of 5050)");
    defaultEncodings.put("MS874", "Windows Thai");
    defaultEncodings.put("MacArabic", "Macintosh Arabic");
    defaultEncodings.put("MacCentralEurope", "Macintosh Latin-2");
    defaultEncodings.put("MacCroatian", "Macintosh Croatian");
    defaultEncodings.put("MacCyrillic", "Macintosh Cyrillic");
    defaultEncodings.put("MacDingbat", "Macintosh Dingbat");
    defaultEncodings.put("MacGreek", "Macintosh Greek");
    defaultEncodings.put("MacHebrew", "Macintosh Hebrew");
    defaultEncodings.put("MacIceland", "Macintosh Iceland");
    defaultEncodings.put("MacRoman", "Macintosh Roman");
    defaultEncodings.put("MacRomania", "Macintosh Romania");
    defaultEncodings.put("MacSymbol", "Macintosh Symbol");
    defaultEncodings.put("MacThai", "Macintosh Thai");
    defaultEncodings.put("MacTurkish", "Macintosh Turkish");
    defaultEncodings.put("MacUkraine", "Macintosh Ukraine");

  }

  /**
   * Initializes the known names for the default encodings. Not all encodings may be
   * available on a specific platform, these encoding will be ignored later.
   *
   * @return the singleton instance of the initialized default encoding names
   */
  protected static Properties getDefaultEncodings ()
  {
    if (defaultEncodings == null)
    {
      defaultEncodings = new Properties();
      addBasicEncodings();
      addExtendedEncodings();

    }
    return defaultEncodings;
  }

  /**
   * Creates a default model containing a selection of encodings.
   *
   * @return The default model.
   */
  public static EncodingComboBoxModel createDefaultModel ()
  {
    final EncodingComboBoxModel ecb = new EncodingComboBoxModel();

    //final String availEncs = getAvailableEncodings();
//
//    if (availEncs.equalsIgnoreCase(AVAILABLE_ENCODINGS_ALL))
//    {
      final Properties encodings = getDefaultEncodings();
      final Enumeration en = encodings.keys();
      while (en.hasMoreElements())
      {
        // add all known properties...
        final String enc = (String) en.nextElement();
        ecb.addEncoding(enc, encodings.getProperty(enc, ENCODING_DEFAULT_DESCRIPTION));
      }
//    }
//    else if (availEncs.equals(AVAILABLE_ENCODINGS_FILE))
//    {
//      final String encFile = getEncodingsDefinitionFile();
//      final InputStream in = ObjectUtilities.getResourceAsStream
//              (encFile, EncodingComboBoxModel.class);
//      if (in == null)
//      {
//        /*Log.warn(new Log.SimpleMessage
//                ("The specified encodings definition file was not found: ", encFile));*/
//      }
//      else
//      {
//        try
//        {
//          final Properties defaultEncodings = getDefaultEncodings();
//          final Properties encDef = new Properties();
//          final BufferedInputStream bin = new BufferedInputStream(in);
//          encDef.load(bin);
//          bin.close();
//          final Enumeration en = encDef.keys();
//          while (en.hasMoreElements())
//          {
//            final String enc = (String) en.nextElement();
//            // if not set to "true"
//            if (encDef.getProperty(enc, "false").equalsIgnoreCase("true"))
//            {
//              // if the encoding is disabled ...
//              ecb.addEncoding
//                      (enc, defaultEncodings.getProperty(enc, ENCODING_DEFAULT_DESCRIPTION));
//            }
//          }
//        }
//        catch (IOException e)
//        {
//          /*Log.warn(new Log.SimpleMessage
//                  ("There was an error while reading the encodings definition file: ", encFile), e);*/
//        }
//      }
//    }
    return ecb;
  }

  /**
   * Returns the index of an encoding.
   *
   * @param encoding the encoding (name).
   * @return The index.
   */
  public int indexOf (final String encoding)
  {
    return encodings.indexOf(new EncodingCarrier(encoding, null));
  }

  /**
   * Returns an encoding.
   *
   * @param index the index.
   * @return The index.
   */
  public String getEncoding (final int index)
  {
    final EncodingCarrier ec = (EncodingCarrier) encodings.get(index);
    return ec.getName();
  }

  /**
   * Returns a description.
   *
   * @param index the index.
   * @return The description.
   */
  public String getDescription (final int index)
  {
    final EncodingCarrier ec = (EncodingCarrier) encodings.get(index);
    return ec.getDescription();
  }


  /**
   * Defines the loader settings for the available encodings shown to the user. The
   * property defaults to AVAILABLE_ENCODINGS_ALL.
   *
   * @return either AVAILABLE_ENCODINGS_ALL, AVAILABLE_ENCODINGS_FILE or
   *         AVAILABLE_ENCODINGS_NONE.
   */
/*  public static String getEncodingsDefinitionFile ()
  {
    return JFreeReportBoot.getInstance().getGlobalConfig().getConfigProperty
            (ENCODINGS_DEFINITION_FILE, ENCODINGS_DEFINITION_FILE_DEFAULT);
  }*/


  /**
   * Defines the loader settings for the available encodings shown to the user. The
   * property defaults to AVAILABLE_ENCODINGS_ALL.
   *
   * @return either AVAILABLE_ENCODINGS_ALL, AVAILABLE_ENCODINGS_FILE or
   *         AVAILABLE_ENCODINGS_NONE.
   */
/*  public static String getAvailableEncodings ()
  {
    return JFreeReportBoot.getInstance().getGlobalConfig().getConfigProperty
            (AVAILABLE_ENCODINGS, AVAILABLE_ENCODINGS_ALL);
  }*/

  public void setSelectedEncoding (final String encoding)
  {
    final int size = encodings.size();
    for (int i = 0; i < size; i++)
    {
      final EncodingCarrier carrier = (EncodingCarrier) encodings.get(i);
      if (encoding.equals(carrier.getName()))
      {
        selectedIndex = i;
        selectedObject = carrier.getDisplayName();
        fireContentsChanged(-1, -1);
        return;
      }
    }
    // default fall-back to have a valid value ..
    if (size > 0)
    {
      selectedIndex = 0;
      selectedObject = getElementAt(0);
      fireContentsChanged(-1, -1);
    }
  }

  /**
   * Notifies all registered listeners that the content of the model has changed.
   */
  protected void fireContentsChanged (int start, int length)
  {
    if (listDataListeners == null)
    {
      return;
    }
    final ListDataEvent evt = new ListDataEvent(this, ListDataEvent.CONTENTS_CHANGED, 0, getSize());
    for (int i = 0; i < listDataListeners.size(); i++)
    {
      final ListDataListener l = (ListDataListener) listDataListeners.get(i);
      l.contentsChanged(evt);
    }
  }

  public static void main(String[] args)
      throws IOException
  {
    final Properties defaultEncodings = getDefaultEncodings();
    final FileOutputStream out = new FileOutputStream("/tmp/out.txt");
    defaultEncodings.store(out, "Encoding names");
    out.close();
  }

}
