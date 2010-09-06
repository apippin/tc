using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;

namespace MLSFileTrimmer
{
    public class CSVTrimmer
    {
        private String filename = String.Empty;
        private Hashtable mlsFields = new Hashtable();

        public CSVTrimmer(ThirdCouncelorMLSFilesMLSFile xmlData)
        {
            this.filename = xmlData.Name;

            foreach (String s in xmlData.MLSField)
            {
                this.mlsFields.Add(s, -1);
            }
        }

        public void ExtractData(StreamReader readFile, StreamWriter outFile)
        {
            string line;
            string[] row;

            // get headers and create mapping
            if ((line = readFile.ReadLine()) != null)
            {
                row = line.Split('"');
                for (int x = 0; x < row.Length; x++)
                {
                    if (!(row[x].Equals(",")) && !(row[x].Equals("")))
                    {
                        if (this.mlsFields.ContainsKey(row[x]) || this.mlsFields.ContainsKey("*"))
                        {
                            this.mlsFields[row[x]] = x;
                        }
                    }
                }
            }

            // write headers in new file
            ICollection headers = this.mlsFields.Keys;
            StringBuilder sb = new StringBuilder();
            foreach (string s in headers)
            {
                int index = Convert.ToInt32(this.mlsFields[s]);
                if (index >= 0)
                {
                    sb.Append("\"");
                    sb.Append(s);
                    sb.Append("\",");
                }
            }
            //sb.Remove(sb.Length - 1, 1);   // remove trailing comma
            outFile.WriteLine(sb.ToString());

            // only write specified fields in new file
            while ((line = readFile.ReadLine()) != null)
            {
                sb.Remove(0, sb.Length);
                row = line.Split('"');
                foreach (string s in headers)
                {
                    int index = Convert.ToInt32(this.mlsFields[s]);
                    if (index >= 0)
                    {
                        sb.Append("\"");
                        sb.Append(row[index]);
                        sb.Append("\",");
                    }
                }
                //sb.Remove(sb.Length - 1, 1);   // remove trailing comma
                outFile.WriteLine(sb.ToString());
            }
        }
    }
}
