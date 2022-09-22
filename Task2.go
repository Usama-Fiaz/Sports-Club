package main

import "fmt"

type employee struct {
	name     string
	salary   int
	position string
}
type company struct {
	companyName string
	employees   []employee
}

func main() {
	// Pers1 specification
	var e1 employee
	var e2 employee
	var e3 employee

	e1.name = "Usama"
	e1.salary = 80000
	e1.position = "Full Stack Developer"

	e2.name = "Talha"
	e2.salary = 100000
	e2.position = "Android Developer"

	e3.name = "Tahaa"
	e3.salary = 60000
	e3.position = "Software Developer"

	var comp company
	comp.companyName = "Tetra"
	comp.employees = []employee{e1, e2, e3}
	Print_Company_Details(comp)
}

func Print_Company_Details(comp company) {
	fmt.Println("\n************************ Printing Company Information ✅ ************************")
	fmt.Println("Company Name: ", comp.companyName)
	fmt.Println("********* Printing Company's Employees Information ✅ *********\n")
	var total = len(comp.employees) //Taking Total Employees
	for count := 0; count < total; count++ {
		fmt.Println("Employee ", count+1, " Name : ", comp.employees[count].name)
		fmt.Println("Employee ", count+1, " Salary : ", comp.employees[count].salary)
		fmt.Println("Employee ", count+1, " Position : ", comp.employees[count].position, "\n")
	}

}
