/*
 * ConverterForm.java
 *
 * Created on 29 May 2007, 13:30
 */

package jconverter;

import java.io.*;
import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.filechooser.*;
import java.nio.charset.Charset;

/**
 *
 * @author  moffats
 */
public class ConverterForm extends javax.swing.JFrame {
    
    /** Creates new form ConverterForm */
    public ConverterForm() {
        initComponents();
    }
    
    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    // <editor-fold defaultstate="collapsed" desc=" Generated Code ">//GEN-BEGIN:initComponents
    private void initComponents() {
        lblMigrationScript = new javax.swing.JLabel();
        lblTopBlank = new javax.swing.JLabel();
        lblTopRight = new javax.swing.JLabel();
        lblInputFile = new javax.swing.JLabel();
        txtInputFileName = new javax.swing.JTextField();
        btnFindInput = new javax.swing.JButton();
        lblOutputFile = new javax.swing.JLabel();
        txtOutputFileName = new javax.swing.JTextField();
        btnFindOutput = new javax.swing.JButton();
        lblEncoding = new javax.swing.JLabel();
        comboEncoding = new javax.swing.JComboBox();
        btnDoConvert = new javax.swing.JButton();

        getContentPane().setLayout(new java.awt.GridLayout(4, 3));

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("JConverter");
        lblMigrationScript.setText("Joomla! Migration Script Converter");
        getContentPane().add(lblMigrationScript);

        getContentPane().add(lblTopBlank);

        getContentPane().add(lblTopRight);

        lblInputFile.setText("Input:");
        getContentPane().add(lblInputFile);

        txtInputFileName.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                txtInputFileNameActionPerformed(evt);
            }
        });

        getContentPane().add(txtInputFileName);

        btnFindInput.setText("Find File...");
        btnFindInput.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                btnFindInputMouseClicked(evt);
            }
        });

        getContentPane().add(btnFindInput);

        lblOutputFile.setText("Output:");
        getContentPane().add(lblOutputFile);

        txtOutputFileName.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                txtOutputFileNameActionPerformed(evt);
            }
        });

        getContentPane().add(txtOutputFileName);

        btnFindOutput.setText("Set File...");
        btnFindOutput.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                btnFindOutputMouseClicked(evt);
            }
        });

        getContentPane().add(btnFindOutput);

        lblEncoding.setText("Encoding:");
        getContentPane().add(lblEncoding);

        comboEncoding.setEditable(true);
        comboEncoding.setModel(new javax.swing.DefaultComboBoxModel(new String[] { "ASCII", "Cp1252", "ISO-8859-1", "ISO-8859-15", "UTF-8", "UTF-16", "Cp1250", "Cp1251", "Cp1253", "Cp1254", "Cp1255", "Cp1256", "Cp1257", "Cp1258", "ISO-8859-2", "ISO-8859-3", "ISO-8859-4", "ISO-8859-5", "ISO-8859-6", "ISO-8859-7", "ISO-8859-8", "ISO-8859-9", "ISO-8859-13", "MS932", "EUC-JP", "EUC-JP-LINUX", "SJIS", "ISO-2022-JP", "MS936", "GB18030", "EUC_CN", "GB2312", "GBK", "ISCII91", "ISO-2022-CN-GB", "MS949", "EUC_KR", "ISO-2022-KR", "MS950", "EUC-TW", "ISO-2022-CN-CNS", "Big5", "Big5-HKSCS", "TIS-620", "KOI8-R", "Big5_Solaris", "Cp037", "Cp273", "Cp277", "Cp278", "Cp280", "Cp284", "Cp285", "Cp297", "Cp420", "Cp424", "Cp437", "Cp500", "Cp737", "Cp775", "Cp838", "Cp850", "Cp852", "Cp855", "Cp856", "Cp857", "Cp858", "Cp860", "Cp861", "Cp862", "Cp863", "Cp864", "Cp865", "Cp866", "Cp868", "Cp869", "Cp870", "Cp871", "Cp874", "Cp875", "Cp918", "Cp921", "Cp922", "Cp930", "Cp933", "Cp935", "Cp937", "Cp939", "Cp942", "Cp942C", "Cp943", "Cp943C", "Cp948", "Cp949", "Cp949C", "Cp950", "Cp964", "Cp970", "Cp1006", "Cp1025", "Cp1026", "Cp1046", "Cp1097", "Cp1098", "Cp1112", "Cp1122", "Cp1123", "Cp1124", "Cp1140", "Cp1141", "Cp1142", "Cp1143", "Cp1144", "Cp1145", "Cp1146", "Cp1147", "Cp1148", "Cp1149", "Cp1381", "Cp1383", "Cp33722", "MS874", "MacArabic", "MacCentralEurope", "MacCroatian", "MacCyrillic", "MacDingbat", "MacGreek", "MacHebrew", "MacIceland", "MacRoman", "MacRomania", "MacSymbol", "MacThai", "MacTurkish", "MacUkraine" }));
        comboEncoding.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                comboEncodingActionPerformed(evt);
            }
        });

        getContentPane().add(comboEncoding);

        btnDoConvert.setText("Convert");
        btnDoConvert.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnDoConvertActionPerformed(evt);
            }
        });
        btnDoConvert.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                btnDoConvertMouseClicked(evt);
            }
        });

        getContentPane().add(btnDoConvert);

        pack();
    }
    // </editor-fold>//GEN-END:initComponents

    private void comboEncodingActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_comboEncodingActionPerformed
// TODO add your handling code here:
    }//GEN-LAST:event_comboEncodingActionPerformed
    
    private void btnDoConvertMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_btnDoConvertMouseClicked
        //this.lblEncoding.setText((String)this.comboEncoding.getSelectedItem());
        this.convertFile(this.txtInputFileName.getText(),this.txtOutputFileName.getText(), (String)this.comboEncoding.getSelectedItem());
        final JDialog dialog = new JDialog(this, "Conversion Complete", true);
        JLabel label = new JLabel("Conversion Complete!");
        dialog.add(label);
        JButton okbutton = new JButton("Close");
        okbutton.addActionListener( new ActionListener()  {
            public void actionPerformed(ActionEvent e)  {
                dialog.dispose();
            }
        } );
        dialog.add(okbutton);
        dialog.pack();
        dialog.setVisible(true);
    }//GEN-LAST:event_btnDoConvertMouseClicked
    
    private void btnFindOutputMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_btnFindOutputMouseClicked
        JFileChooser chooser = new JFileChooser();
        int returnVal = chooser.showSaveDialog(this);
        if (returnVal == JFileChooser.APPROVE_OPTION) {
            File file = chooser.getSelectedFile();
            this.txtOutputFileName.setText(file.getAbsolutePath());
        }
    }//GEN-LAST:event_btnFindOutputMouseClicked
    
    private void btnFindInputMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_btnFindInputMouseClicked
        JFileChooser chooser = new JFileChooser();
        int returnVal = chooser.showOpenDialog(this);
        if (returnVal == JFileChooser.APPROVE_OPTION) {
            File file = chooser.getSelectedFile();
            this.txtInputFileName.setText(file.getAbsolutePath());
        }
    }//GEN-LAST:event_btnFindInputMouseClicked
    
    private void btnDoConvertActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnDoConvertActionPerformed
// TODO add your handling code here:
    }//GEN-LAST:event_btnDoConvertActionPerformed
    
    private void txtOutputFileNameActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_txtOutputFileNameActionPerformed
// TODO add your handling code here:
    }//GEN-LAST:event_txtOutputFileNameActionPerformed
    
    private void txtInputFileNameActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_txtInputFileNameActionPerformed
// TODO add your handling code here:
    }//GEN-LAST:event_txtInputFileNameActionPerformed
    
    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                new ConverterForm().setVisible(true);
            }
        });
    }
    
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton btnDoConvert;
    private javax.swing.JButton btnFindInput;
    private javax.swing.JButton btnFindOutput;
    private javax.swing.JComboBox comboEncoding;
    private javax.swing.JLabel lblEncoding;
    private javax.swing.JLabel lblInputFile;
    private javax.swing.JLabel lblMigrationScript;
    private javax.swing.JLabel lblOutputFile;
    private javax.swing.JLabel lblTopBlank;
    private javax.swing.JLabel lblTopRight;
    private javax.swing.JTextField txtInputFileName;
    private javax.swing.JTextField txtOutputFileName;
    // End of variables declaration//GEN-END:variables
    
    
    private void convertFile(String inputFilename, String outputFilename,
            String originalEncoding) {
        try {
            StringBuffer buffer = new StringBuffer();
            FileInputStream fis = new FileInputStream(inputFilename);
            InputStreamReader isr = new InputStreamReader(fis, originalEncoding);
            byte[] contents = new byte[fis.available()];
            Reader in = new BufferedReader(isr);
            int ch;
            while ((ch = in.read()) > -1) {
                buffer.append((char)ch);
            }
            isr.close();

            FileOutputStream fos = new FileOutputStream(outputFilename);
            Writer out = new OutputStreamWriter(fos, "UTF8");

            out.write(buffer.toString());
            out.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}