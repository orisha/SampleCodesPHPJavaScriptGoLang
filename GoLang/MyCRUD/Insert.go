// it will get an struct, which must identical to database table, and
// make an insert or update ( depend if it has ID set ) SQL statement ...





package MyCRUD

import (
	"database/sql"
	"fmt"
	"github.com/fatih/structs"
	_ "github.com/go-sql-driver/mysql"
	"github.com/jmoiron/sqlx"
	"reflect"
	str "strings"
	"time"
	_ "time"
)

var LastInsertedID int64

func Insert( conn *sqlx.DB, data interface{} ) (bool, error)  {

	var DbValues []string
	var DbNames []string

	Name := structs.Name(data)

	structData := structs.New(data)

	isUpdate := false
	var UpdateID, SQL string

	for _, field := range structData.Fields() {



		strValues := ""

		if field.Kind() == reflect.Struct  {

			strctFieldValid := structs.New(field.Value()).Field("Valid")

			if strctFieldValid.Value() != true {

				strValues = " NULL "

			} else {



				var _Value string

				var _Type reflect.Kind
				var _Name string


				for _, fieldName := range field.Fields(){

					if fieldName.Name() != "Valid" {

						_Type  =  structs.New( field.Value() ).Field( fieldName.Name() ).Kind()
						_Name =  fmt.Sprint ( structs.New( field.Value() ).Field( fieldName.Name() ).Name() )
						_Value =  fmt.Sprint ( structs.New( field.Value() ).Field( fieldName.Name() ).Value() )

					}

				}

				if field.Name() == "ID"{
					isUpdate = true
					UpdateID = _Value
				}

				switch _Type {

				case reflect.Int, reflect.Int64, reflect.Float32, reflect.Float64 :

						strValues = " " +  _Value + " "


				case reflect.String:


						strValues += " '"
						strValues += _Value
						strValues += "' "

				case reflect.Bool:

						strValues = " TRUE "

				case reflect.Struct:


					if _Name == "Time"{

						_Value = dateParserFormater(_Value)

					}

						strValues += " '"
						strValues += _Value
						strValues += "' "

				default:

					strValues = " NULL DF " +  field.Name()

				}
			}


		} else {


			switch field.Kind() {

			case reflect.Int, reflect.Int64, reflect.Float32 :
				if field.IsZero(){
					strValues = " 0 "
				} else {

					strValues = " " +   fmt.Sprint (field.Value() ) + " "
				}


			case reflect.String:

				if field.IsZero(){
					strValues = "''"
				} else {

					strValues = " '"
					strValues += field.Value().(string)
					strValues += "'  "
				}


			case reflect.Bool:

				if field.Value() == false || field.IsZero() {

					strValues = " 0 "
				} else {
					strValues = " TRUE "

				}

			default:
				strValues = " NULL DF " + field.Name()


			}

		}


		tagValue := field.Tag("db")


		if tagValue == ""{
			tagValue = str.ToLower(field.Name())
		}

		if tagValue != "id" {

			DbNames = append(DbNames, tagValue)
			DbValues = append(DbValues, strValues)
		}


	}



	Name = str.Replace(Name, "EntityInput", " ", -1)
	Name = str.Replace(Name, "Entity", " ", -1)

	if (isUpdate == false){


		SQL = "INSERT INTO "
		SQL += Name
		SQL += "(`"
		SQL += str.Join(DbNames, "`, `")
		SQL += "` ) VALUES ( "
		SQL += str.Join(DbValues, ", ")
		SQL += ")"

	} else {

		SQL = "UPDATE "
		SQL += Name
		SQL += " SET "

		UpStr := ""
		for kk, _ := range DbNames {

			UpStr += "`" + DbNames[kk] + "` = " + DbValues[kk] + ", "
		}
		strlen := len(UpStr) - 2
		SQL += UpStr[0:strlen]
		SQL += " WHERE `id` = '" + UpdateID + "';"


	}


	qu, err := conn.Exec(SQL)


	if err != nil{

		fmt.Println("Err: %v", err)
		return false, err
	}

	if isUpdate == false {

		LastInsertedID, err = qu.LastInsertId()
		ID := reflect.ValueOf(sql.NullInt64{Int64: LastInsertedID, Valid: true})
		reflect.ValueOf(data).Elem().FieldByName("ID").Set(ID)
	}

	if err != nil{

		fmt.Println("Err: %v", err)
		return false, err
	}

	return true, err
}

func dateParserFormater( Val string) string {


	dt := str.Split(Val, ".")
	_Value := dt[0]

	dt = str.Split(_Value, " -")

	_Value = dt[0]

	dt = str.Split(_Value, " +")
	tmfrmt := "2006-01-02 15:04:05"

	tm, eee := time.Parse( tmfrmt, dt[0])

	if eee != nil {

		fmt.Println( "ERRRRR :"  + eee.Error() )
		return ""
	}

	_Value = tm.Format(tmfrmt)

	return _Value
}
