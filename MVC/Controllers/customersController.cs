using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Entity;
using System.Linq;
using System.Net;
using System.Web;
using System.Web.Mvc; 
using MVC.Models;
using System.Text.RegularExpressions; //pour utilisation des regex


namespace MVC.Controllers
{
	public class customersController : Controller
	{
		//Déclaration des regex
		string regexName = @"^[A-Za-zéèàêâôûùïüç\-]+$";
		string regexSubject = @"^[A-Z0-9a-zéèàêâôûùïüç '\-]+$";
		string regexMail = @"[0-9a-zA-Z\.\-]+@[0-9a-zA-Z\.\-]+.[a-zA-Z]{2,4}";
		string regexPhone = @"^[0][0-9]{9}";

		private agendaEntities2 db = new agendaEntities2();

		// GET: customers
		public ActionResult Index()
		{
			return View(db.customers.ToList());
		}

		//Vue de l'ôpération réussi
		public ActionResult Succes()
		{
			return View("Succes");
		}

		// GET: customers/Create
		public ActionResult Create()
		{
			return View();
		}

		// POST: customers/Create
		// Afin de déjouer les attaques par sur-validation, activez les propriétés spécifiques que vous voulez lier. Pour 
		// plus de détails, voir  https://go.microsoft.com/fwlink/?LinkId=317598.
		[HttpPost]
		[ValidateAntiForgeryToken]
		public ActionResult Create([Bind(Include = "idCustomer,lastname,firstname,mail,phoneNumber,budget,subject")] customers customers)
		{
			//Vérification que le champ lastname n'est pas null ou vide
			if (!String.IsNullOrEmpty(customers.lastname)) //si le champ lastname n'est pas vide ou null on vérifie la validité de l'entrée
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(customers.lastname, regexName)) //si l'entrée utilisateur ne passe pas la regex ajout d'un message d'erreur
				{
					//Message d'erreur
					ModelState.AddModelError("lastname", "Ecrire un nom valide");
				}
			}
			else
			{
				//Message d'erreur si le champ lastname est vide ou null
				ModelState.AddModelError("lastname", "Ecrire un nom");
			}
			//Vérification que le champ firstname n'est pas null ou vide
			if (!String.IsNullOrEmpty(customers.firstname))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(customers.firstname, regexName))
				{
					//Message d'erreur
					ModelState.AddModelError("firstname", "Ecrire un prénom valide");
				}
			}
			else
			{
				//Message d'erreur
				ModelState.AddModelError("firstname", "Ecrire un prénom");
			}
			//Vérification que le champ mail n'est pas null ou vide
			if (!String.IsNullOrEmpty(customers.mail))
			{
				//Creation de la variable isAlreadyUsed qui permet de verifier qu'un mail n'est pas attribuer a deux client different
				var isAlreadyUsed = db.customers.Where(cus => cus.mail == customers.mail).SingleOrDefault();
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(customers.mail, regexMail))
				{
					//Message d'erreur
					ModelState.AddModelError("mail", "Ecrire un mail valide");
				}
				else if (isAlreadyUsed != null)
				{
					ModelState.AddModelError("Mail", "un client a déjà la même adresse mail");
				}
			}
			else
			{
				//Message d'erreur
				ModelState.AddModelError("mail", "Ecrire un mail");
			}
			//Vérification que le champ phoneNumber n'est pas null ou vide
			if (!String.IsNullOrEmpty(customers.phoneNumber))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(customers.phoneNumber, regexPhone))
				{
					//Message d'erreur
					ModelState.AddModelError("phoneNumber", "Ecrire un téléphone valide");
				}
			}
			else
			{
				//Message d'erreur
				ModelState.AddModelError("phoneNumber", "Ecrire un téléphone");
			}
			//Vérification que le champ lastname n'est pas null ou vide
			if (!String.IsNullOrEmpty(customers.subject)) //si le champ lastname n'est pas vide ou null on vérifie la validité de l'entrée
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(customers.subject, regexSubject)) //si l'entrée utilisateur ne passe pas la regex ajout d'un message d'erreur
				{
					//Message d'erreur
					ModelState.AddModelError("subject", "Ecrire un sujet valide");
				}
			}
			else
			{
				//Message d'erreur si le champ lastname est vide ou null
				ModelState.AddModelError("subject", "Ecrire un sujet");
			}

			//Si il n'y a pas d'erreur
			if (ModelState.IsValid)
			{
				db.customers.Add(customers);
				db.SaveChanges();
				return RedirectToAction("Succes");
			}
			else
			{
				return View(customers);
			}
		}

		// GET: customers/Edit/5
		public ActionResult Edit(int? id)
		{
			customers customers = db.customers.Find(id);
			if (customers == null)
			{
				return View("PageNotFound");
			}
			return View(customers);
		}

		// POST: customers/Edit/5
		// Afin de déjouer les attaques par sur-validation, activez les propriétés spécifiques que vous voulez lier. Pour 
		// plus de détails, voir  https://go.microsoft.com/fwlink/?LinkId=317598.
		[HttpPost]
		[ValidateAntiForgeryToken]
		public ActionResult Edit([Bind(Include = "idCustomer,lastname,firstname,mail,phoneNumber,budget,subject")] customers customers)
		{
			//Vérification que le champ lastname n'est pas null ou vide
			if (!String.IsNullOrEmpty(customers.lastname)) //si le champ lastname n'est pas vide ou null on vérifie la validité de l'entrée
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(customers.lastname, regexName)) //si l'entrée utilisateur ne passe pas la regex ajout d'un message d'erreur
				{
					//Message d'erreur
					ModelState.AddModelError("lastname", "Ecrire un nom valide");
				}
			}
			else
			{
				//Message d'erreur si le champ lastname est vide ou null
				ModelState.AddModelError("lastname", "Ecrire un nom");
			}
			//Vérification que le champ firstname n'est pas null ou vide
			if (!String.IsNullOrEmpty(customers.firstname))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(customers.firstname, regexName))
				{
					//Message d'erreur
					ModelState.AddModelError("firstname", "Ecrire un prénom valide");
				}
			}
			else
			{
				//Message d'erreur
				ModelState.AddModelError("firstname", "Ecrire un prénom");
			}
			//Vérification que le champ mail n'est pas null ou vide
			if (!String.IsNullOrEmpty(customers.mail))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(customers.mail, regexMail))
				{
					//Message d'erreur
					ModelState.AddModelError("mail", "Ecrire un mail valide");
				}
			}
			else
			{
				//Message d'erreur
				ModelState.AddModelError("mail", "Ecrire un mail");
			}
			//Vérification que le champ phoneNumber n'est pas null ou vide
			if (!String.IsNullOrEmpty(customers.phoneNumber))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(customers.phoneNumber, regexPhone))
				{
					//Message d'erreur
					ModelState.AddModelError("phoneNumber", "Ecrire un téléphone valide");
				}
			}
			else
			{
				//Message d'erreur
				ModelState.AddModelError("phoneNumber", "Ecrire un téléphone");
			}
			if(customers.budget <= 0)
			{
				//Message d'erreur
				ModelState.AddModelError("budget", "Le budget doit étre possitif");
			}
			//Vérification que le champ lastname n'est pas null ou vide
			if (!String.IsNullOrEmpty(customers.subject)) //si le champ lastname n'est pas vide ou null on vérifie la validité de l'entrée
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(customers.subject, regexSubject)) //si l'entrée utilisateur ne passe pas la regex ajout d'un message d'erreur
				{
					//Message d'erreur
					ModelState.AddModelError("subject", "Ecrire un sujet valide");
				}
			}
			else
			{
				//Message d'erreur si le champ lastname est vide ou null
				ModelState.AddModelError("subject", "Ecrire un sujet");
			}

			if (ModelState.IsValid)
			{
				db.Entry(customers).State = EntityState.Modified;
				db.SaveChanges();
				return RedirectToAction("Succes");
			}
			else
			{
				return View(customers);
			}
		}

		// GET: customers/Delete/5
		public ActionResult Delete(int? id)
		{
			if (id == null)
			{
				return View("Error");
			}
			customers customers = db.customers.Find(id);
			if (customers == null)
			{
				return View("PageNotFound");
			}
			return View(customers);
		}

		// POST: customers/Delete/5
		[HttpPost, ActionName("Delete")]
		[ValidateAntiForgeryToken]
		public ActionResult DeleteConfirmed(int id)
		{
			////Permet de DELET ON CASCADE sans modifier la Base de Donnée
			//if (ModelState.IsValid)
			//{
			//	List<appointments> list = db.appointments.Where(d => d.idCustomer == id).ToList();
			//	db.appointments.RemoveRange(list);
			//	db.SaveChanges();
			//	db.customers.Remove(db.customers.Find(id));
			//	db.SaveChanges();
			//}
			//	return RedirectToAction("index");
			if (ModelState.IsValid)
			{
				customers customers = db.customers.Find(id);
				db.customers.Remove(customers);
				db.SaveChanges();
				return RedirectToAction("Index");
			}
			else
			{
				return View();
			}
		}

		protected override void Dispose(bool disposing)
		{
			if (disposing)
			{
				db.Dispose();
			}
			base.Dispose(disposing);
		}
	}
}