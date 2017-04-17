import java.io.*;
import java.util.*;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

public class ExtractLinks {

	public static void main(String[] args) throws Exception {
		// TODO Auto-generated method stub
		//System.out.println(System.getProperty("user.dir"));
		final String FILENAME = "/Users/Xenon/Documents/Work/HW4/NBCNewsData/mapNBCNewsDataFile.csv";
		BufferedReader br = null;
		FileReader fr = null;
		HashMap<String, String> fileUrlMap=new HashMap<String,String>();
		HashMap<String, String> urlFileMap=new HashMap<String,String>();
		try {
			fr = new FileReader(FILENAME);
			br = new BufferedReader(fr);
			String sCurrentLine;
			br = new BufferedReader(new FileReader(FILENAME));
			while ((sCurrentLine = br.readLine()) != null) {
				String arr[]=sCurrentLine.split(",");
				fileUrlMap.put(arr[0], arr[1]);
				urlFileMap.put(arr[1], arr[0]);
			}
		} catch (IOException e) {
			e.printStackTrace();
		}
		final String dirPath="/Users/Xenon/Documents/Work/HW4/NBCNewsData/NBCNewsDownloadData/NBCNewsDownloadData";
		File dir=new File(dirPath);
		Set<String> edges=new HashSet<String>();
		for(File file:dir.listFiles()){
			Document doc=Jsoup.parse(file,"UTF-8",fileUrlMap.get(file.getName()));
			Elements links=doc.select("a[href]");
			for(Element link:links)
			{
				String url=link.attr("abs:href").trim();
				if(urlFileMap.containsKey(url))
				{
					edges.add(file.getName()+" "+urlFileMap.get(url));
				}
			}
		}
		
		try{
		    PrintWriter writer = new PrintWriter("edges.txt", "UTF-8");
			for(String s:edges){
				writer.println(s);
			}
			writer.flush();
			writer.close();
			System.out.println("edges.txt created successfully!");
		    writer.close();
		} catch (IOException e) {
		   // do something
		}
	}
}
