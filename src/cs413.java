import java.sql.*;
import java.util.Scanner;

public class cs413 {
	static Scanner scan = new Scanner(System.in);
	
	public static void main(String[] args) {
		Connection con = null;
		
		try {
			//load JBDC driver
			Class.forName("com.mysql.jdbc.Driver"); 
			System.out.println("Driver Loaded");
			
			//connect to the DB
			con = connect(con);
			
			//drop the tables if they are already existing
			drop(con);
			
			//create tables
			createTables(con);
			
			//insert employee tuples
			insertToEmployee(con, "21601737", "qwerty", "Tarik", "1998-08-29", "Ankara", "senior", "3.02f");
			insertToEmployee(con, "21212121", "qwerty", "ooo", "2000-05-08", "Ankara", "sophomore", "2.75f");
			insertToEmployee(con, "20000000", "qwerty", "test", "2000-02-02", "Ankara", "junior", "2.55f");
			
			//insert company tuples
			insertToCompany(con, "C101", "tubitak", "2");
			insertToCompany(con, "C102", "aselsan", "5");
			insertToCompany(con, "C103", "havelsan", "3");
			insertToCompany(con, "C104", "microsoft", "5");
			insertToCompany(con, "C105", "google", "3");
			insertToCompany(con, "C106", "tai", "4");
			insertToCompany(con, "C107", "milsoft", "2");
			
			//insert apply tuples
			insertToApply(con, "21601737", "C105");
			insertToApply(con, "21212121", "C107");
			insertToApply(con, "20000000", "C107");
			
			//display all employees
			allEmployees(con);
		}
		catch(ClassNotFoundException e) { //exception for JBDC driver
			throw new IllegalStateException("Cannot find the driver", e);
		}
		finally { //closing
			try {
				if(con!=null)
					con.close();
		    }catch( SQLException e ) {
		    	throw new IllegalStateException("Cannot close the connection", e);
		    }
		}
	}
	
	public static Connection connect( Connection con) {
		while(true) { //loop until valid credentials are entered
			//Getting credentials and creating DB URL
			//System.out.println("Enter username: ");
			String username = "emin.kaplan"; //scan.nextLine();
			//System.out.println("Enter password: ");
			String password = "mfhAGKtw"; //scan.nextLine();
			String usernameForURL = username.replace('.', '_');
			String url = "jdbc:mysql://dijkstra.ug.bcc.bilkent.edu.tr:3306/" + usernameForURL;
			try {
				System.out.println("Connecting.....");
				con = DriverManager.getConnection( url, username, password );
				System.out.println("Connected......");
				return con;
			}
			catch( SQLException e ) { //exception for connection
				System.out.println("Cannot connect to the database, please check your credentials");
			}
		}
	}
	
	public static void createTables( Connection con ) { 
		// this is hardcoded because I'm assuming there won't be any configuration 
		// changes to the tables, or that new tables won't be added
		try {
			System.out.println("Creating the tables");
		    Statement stmt = con.createStatement();
		    
		    //SQL strings to create tables. I have only made the primary keys not null
		    String employeeSql = "CREATE TABLE employee(" +
		                   " eid CHAR(12) not NULL, " +
		                   " password CHAR(12) not NULL, " +
		                   " sname VARCHAR(50), " + 
		                   " bdate DATE, " + 
		                   " scity VARCHAR(20), " + 
		                   " year CHAR(20), " + 
		                   " gpa FLOAT, " + 
		                   " PRIMARY KEY ( eid ) " +
		                   " ) ENGINE=INNODB";
		    String companySql = "CREATE TABLE company(" +
	                   " cid CHAR(8) not NULL, " +
	                   " cname VARCHAR(20), " + 
	                   " quota INT, " +
	                   " PRIMARY KEY ( cid ) " +
	                   " ) ENGINE=INNODB";
		    String applySql = "CREATE TABLE apply(" +
		    		   " eid CHAR(12) not NULL, " +   
		    		   " cid CHAR(8) not NULL, " +
		    		   " PRIMARY KEY ( eid, cid ), " +
		    		   " FOREIGN KEY (eid) REFERENCES employee(eid), " +
		    		   " FOREIGN KEY (cid) REFERENCES company(cid) " +
	                   " ) ENGINE=INNODB";
		    
		    stmt.executeUpdate(employeeSql);
		    stmt.executeUpdate(companySql);
		    stmt.executeUpdate(applySql);
		    System.out.println("Created the tables");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot create tables", e);
		}
	}
	
	public static void drop( Connection con ) { //this is also hardcoded for the same reasons as createTables
		try {
			System.out.println("Dropping the existing tables");
		    Statement stmt = con.createStatement();
		    
		    //SQL strings to drop tables
		    String employeeSql = "DROP TABLE IF EXISTS employee";
		    String companySql = "DROP TABLE IF EXISTS company"; 
		    String applySql = "DROP TABLE IF EXISTS apply"; 
		    
		    /* we drop the apply table first since it has foreign key references and trying to delete the others first
		       will give an error because of that. We could have also avoided that error by adding " CASCADE" after "employee"
		       and "company" in their respective SQL statements. */
		    stmt.executeUpdate(applySql);
		    stmt.executeUpdate(employeeSql);
		    stmt.executeUpdate(companySql);
		    System.out.println("Dropped the existing tables");
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot drop tables", e);
		}
	}
	
	public static void insertToEmployee( Connection con, String eid, String password, String sname, String bdate, String scity, String year, String gpa ) {
		boolean valid = true;
		String gpaCorrected = "";
		// error checking
		if( eid == null) {
			System.out.println("employee id can't be empty");
			valid = false;
		}	
		if( password == null) {
			System.out.println("password can't be empty");
			valid = false;
		}		
		else if( eid != null && eid.length() > 12 ) {
			System.out.println("employee id can't be longer than 12 characters");
			valid = false;
		}
		else if( password != null && password.length() > 12 ) {
			System.out.println("password can't be longer than 12 characters");
			valid = false;
		}
		else if( sname != null && sname.length() > 50 ) {
			System.out.println("employee name can't be longer than 50 characters");
			valid = false;
		}
		else if( bdate != null && ( bdate.length() != 10 || bdate.charAt(4) != '-' || bdate.charAt(7) != '-' ) ) {
			System.out.println("birth date should be in yyyy-mm-dd format (when inserting)");
			valid = false;
		}
		else if( scity != null && scity.length() > 20 ) {
			System.out.println("employee city can't be longer than 20 characters");
			valid = false;
		}
		else if( year != null && year.length() > 20 ) {
			System.out.println("year can't be longer than 20 characters");
			valid = false;
		}
		else if( gpa != null ){
			try{
				gpaCorrected = String.valueOf(Float.parseFloat(gpa));
			}
			catch( NumberFormatException e) {
				throw new IllegalStateException("gpa should be a float", e);
			}
		}
		
		// insert
		if( valid ) {
			try {
				System.out.println("Inserting " + eid + " into employee");
			    Statement stmt = con.createStatement();
			    //SQL string to insert
			    String sql = "INSERT INTO employee (eid, password, sname, bdate, scity, year, gpa) VALUES (" +
			    			 "'" + eid + "'," +
			    			 "'" + password + "'," +
			    			 "'" + sname + "'," +
			    			 "'" + bdate + "'," +
			    			 "'" + scity + "'," +
			    			 "'" + year + "'," +
			    			 "'" + gpaCorrected + "'" +
			    			 " )";
			    
			    stmt.executeUpdate(sql);
			    System.out.println("Inserted " + eid + " into employee");
			}
			catch( SQLException e ) {
				throw new IllegalStateException("Cannot insert " + eid + " into employee", e);
			}
		}
	}
	
	public static void insertToCompany( Connection con, String cid, String cname, String quota ) {
		boolean valid = true;
		// error checking
		if( cid == null) {
			System.out.println("company id can't be empty");
			valid = false;
		}		
		else if( cid != null && cid.length() > 8 ) {
			System.out.println("company id can't be longer than 8 characters");
			valid = false;
		}
		else if( cname != null && cname.length() > 20 ) {
			System.out.println("company name can't be longer than 20 characters");
			valid = false;
		}
		else if( quota != null && quota.length() > 11 ) {
			System.out.println("quota can't be longer than 11 digits");
			valid = false;
		}
		
		// insert
		if( valid ) {
			try {
				System.out.println("Inserting " + cid + " into company");
			    Statement stmt = con.createStatement();
			    //SQL string to insert
			    String sql = "INSERT INTO company (cid, cname, quota) VALUES (" +
			    			 "'" + cid + "'," +
			    			 "'" + cname + "'," +
			    			 "'" + quota + "'" +
			    			 " )";
			    
			    stmt.executeUpdate(sql);
			    System.out.println("Inserted " + cid + " into company");
			}
			catch( SQLException e ) {
				throw new IllegalStateException("Cannot insert " + cid + " into company", e);
			}
		}
	}
	
	public static void insertToApply( Connection con, String eid, String cid ) {
		boolean valid = true;
		// error checking
		if( eid == null) {
			System.out.println("employee id can't be empty");
			valid = false;
		}		
		else if( eid != null && eid.length() > 12 ) {
			System.out.println("employee id can't be longer than 12 characters");
			valid = false;
		}
		
		if( cid == null) {
			System.out.println("company id can't be empty");
			valid = false;
		}		
		else if( cid != null && cid.length() > 8 ) {
			System.out.println("company id can't be longer than 8 characters");
			valid = false;
		}
		
		// insert
		if( valid ) {
			try {
				System.out.println("Inserting employee id: " + eid + ", company id: " + cid + " into apply");
			    Statement stmt = con.createStatement();
			    //SQL string to insert
			    String sql = "INSERT INTO apply (eid, cid) VALUES (" +
			    			 "'" + eid + "'," +
			    			 "'" + cid + "'" +
			    			 " )";
			    
			    stmt.executeUpdate(sql);
			    System.out.println("Inserted employee id: " + eid + ", company id: " + cid + " into apply");
			}
			catch( SQLException e ) {
				throw new IllegalStateException("Cannot insert employee id: " + eid + ", company id: " + cid + " into apply", e);
			}
		}
	}
	
	public static void allEmployees(Connection con) { //this is also hardcoded for the same reasons as createTables
		try {
			System.out.println("Displaying all employees:");
		    
			//SQL string to select all employees
		    String sql = "SELECT *, DATE_FORMAT(bdate,'%d.%m.%Y') AS formatted_date FROM employee";
			PreparedStatement stmt = con.prepareStatement(sql);
			ResultSet rs = stmt.executeQuery();
			System.out.printf("%10s", "eid");
			System.out.printf("%10s", "password");
			System.out.printf("%10s", "sname");
			System.out.printf("%15s", "bdate");
			System.out.printf("%12s", "scity");
			System.out.printf("%12s", "year");
			System.out.printf("%10s", "gpa");
			System.out.println();
			while(rs.next()) {
				System.out.printf("%10s", rs.getString(1));
				System.out.printf("%10s", rs.getString(2));
				System.out.printf("%10s", rs.getString(3));
				System.out.printf("%15s", rs.getString(8)); //new column in the result set, formatted_date
				System.out.printf("%12s", rs.getString(5));
				System.out.printf("%12s", rs.getString(6));
				System.out.printf("%10s", rs.getString(7));
				System.out.println();
			}
		    
		}
		catch( SQLException e ) {
			throw new IllegalStateException("Cannot display employees", e);
		}
	}
}