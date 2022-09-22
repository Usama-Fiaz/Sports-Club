package main

import (
	"fmt"
	"strings"
)

type Student struct {
	rollnumber int
	name       string
	address    string
}

func NewStudent(rollno int, name string, address string) *Student {
	s := new(Student)
	s.rollnumber = rollno
	s.name = name
	s.address = address
	return s
}

type StudentList struct {
	list []*Student
}

func (ls *StudentList) CreateStudent(rollno int, name string, address string) *Student {
	st := NewStudent(rollno, name, address)
	ls.list = append(ls.list, st)
	return st
}
func (*StudentList) Print_Method(SL *StudentList) {
	var total = len(SL.list) //Taking Total Students
	for no := 0; no < total; no++ {
		fmt.Println(strings.Repeat("=", 25), "List ", no, strings.Repeat("=", 25))
		fmt.Println("Student ", no+1, " Roll Number : ", SL.list[no].rollnumber)
		fmt.Println("Student ", no+1, " Name : ", SL.list[no].name)
		fmt.Println("Student ", no+1, " Address : ", SL.list[no].address, "\n")
	}
}

func main() {
	student := new(StudentList)
	student.CreateStudent(24, "Asim", "AAAAAA")
	student.CreateStudent(25, "Naveed", "BBBBBB")
	student.Print_Method(student)
}
