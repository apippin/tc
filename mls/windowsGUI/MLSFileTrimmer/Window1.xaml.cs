using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using Microsoft.Win32;
using System.IO;
using FolderBrowserDialog = System.Windows.Forms.FolderBrowserDialog;
using System.Xml.Serialization;
using System.Xml;
using Ionic.Zip;
using System.ComponentModel;
using System.Threading;

namespace MLSFileTrimmer
{
    /// <summary>
    /// Interaction logic for Window1.xaml
    /// </summary>
    public partial class Window1 : Window
    {
        // default to My Documents
        private String currentDirectory = String.Empty;
        private ThirdCouncelorMLSFiles thirdCouncelorXml;
        private BackgroundWorker worker;

        public Window1()
        {
            InitializeComponent();
            
            // read in xml file
            try
            {
                TextReader reader = new StreamReader("MLSRequiredFields.xml");
                XmlSerializer serializer = new XmlSerializer(typeof(ThirdCouncelorMLSFiles));
                this.thirdCouncelorXml = (ThirdCouncelorMLSFiles)serializer.Deserialize(reader);
                reader.Close();
            }
            catch (XmlException ex)
            {
                MessageBox.Show(ex.Message, "XML Parse Error", MessageBoxButton.OK, MessageBoxImage.Error);
            }
            catch (InvalidOperationException ioe)
            {
                MessageBox.Show(ioe.InnerException.Message, "XML Serialization Error", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }


        private void outDirButton_Click(object sender, RoutedEventArgs e)
        {
            FolderBrowserDialog openFolderDialog = new FolderBrowserDialog();

            openFolderDialog.RootFolder = Environment.SpecialFolder.MyDocuments;
            if (openFolderDialog.ShowDialog() == System.Windows.Forms.DialogResult.OK)
            {
                outputDirtextBox.Text = openFolderDialog.SelectedPath;
                currentDirectory = openFolderDialog.SelectedPath;
            }
        }

        private void parseButton_Click(object sender, RoutedEventArgs e)
        {
            // make sure the directory exists
            if (outputDirtextBox.Text.Equals(String.Empty))
            {
                MessageBox.Show("Please select the correct output directory.");
                return;
            }
            else if (!Directory.Exists(outputDirtextBox.Text))
            {
                MessageBox.Show(outputDirtextBox.Text + " does not exist. Please select the correct output directory.");
            }

            // make sure the original files exist
            foreach (ThirdCouncelorMLSFilesMLSFile csvFile in this.thirdCouncelorXml.CSVFiles)
            {
                if (!File.Exists(this.currentDirectory + "\\" + csvFile.Name))
                {
                    MessageBox.Show(csvFile.Name + " does not exist.  Are you sure you have the right directory?");
                    return;
                }
            }

            // TODO: move to new background thread, post progress dialog
            this.worker = new BackgroundWorker();
            this.worker.DoWork +=new DoWorkEventHandler(worker_DoWork);
            this.worker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(worker_RunWorkerCompleted);
            this.worker.RunWorkerAsync();

            this.trimmingLabel.Visibility = Visibility.Visible;
        }

        private void worker_DoWork(object sender, DoWorkEventArgs e)
        {
            // create new zip file
            using (ZipFile zip = new ZipFile())
            {

                // trim each csv file
                foreach (ThirdCouncelorMLSFilesMLSFile csvFile in this.thirdCouncelorXml.CSVFiles)
                {
                    string newFilename = this.currentDirectory + "\\" + csvFile.Name;
                    string origFilename = newFilename + ".orig";
                    File.Move(newFilename, origFilename);

                    using (StreamReader origFile = new StreamReader(origFilename))
                    {
                        using (StreamWriter newFile = new StreamWriter(newFilename))
                        {
                            CSVTrimmer csvTrimmer = new CSVTrimmer(csvFile);
                            csvTrimmer.ExtractData(origFile, newFile);
                        }
                    }
                    // add new to zip file
                    zip.AddItem(newFilename, "");
                }
                zip.Save(this.currentDirectory + "\\" + "EQZipFile.zip");
            }

            Thread.Sleep(1000);

            // delete csv files
            StringBuilder sb = new StringBuilder();
            foreach (ThirdCouncelorMLSFilesMLSFile csvFile in this.thirdCouncelorXml.CSVFiles)
            {
                sb.Remove(0, sb.Length);
                sb.Append(this.currentDirectory);
                sb.Append("\\");
                sb.Append(csvFile.Name);
                File.Delete(sb.ToString());
                sb.Append(".orig");
                File.Delete(sb.ToString());
            }
        }

        private void worker_RunWorkerCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            this.trimmingLabel.Visibility = Visibility.Hidden;
            this.finishedLabel.Visibility = Visibility.Visible;
        }
    }
}
