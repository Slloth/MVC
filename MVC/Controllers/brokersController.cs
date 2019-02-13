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
    public class brokersController : Controller
    {
		string regexName = @"^[A-Za-zéèàêâôûùïüç\-]+$";
		string regexMail = @"[0-9a-zA-Z\.\-]+@[0-9a-zA-Z\.\-]+.[a-zA-Z]{2,4}";
		string regexPhone = @"^[0][0-9]{9}";

		private agendaEntities2 db = new agendaEntities2();

		// GET: brokers
		public ActionResult Index()
        {
            return View(db.brokers.ToList());
        }

		//Vue de l'ôpération réussi
		public ActionResult Succes()
		{
			return View("Succes");
		}

		// GET: brokers/Create
		public ActionResult Create()
        {
            return View();
        }

        // POST: brokers/Create
        // Afin de déjouer les attaques par sur-validation, activez les propriétés spécifiques que vous voulez lier. Pour 
        // plus de détails, voir  https://go.microsoft.com/fwlink/?LinkId=317598.
        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult Create([Bind(Include = "idBroker,lastname,firstname,mail,phoneNumber")] brokers brokers)
        {
			//Vérification que le champ lastname n'est pas null ou vide
			if (!String.IsNullOrEmpty(brokers.lastname)) //si le champ lastname n'est pas vide ou null on vérifie la validité de l'entrée
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(brokers.lastname, regexName)) //si l'entrée utilisateur ne passe pas la regex ajout d'un message d'erreur
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
			if (!String.IsNullOrEmpty(brokers.firstname))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(brokers.firstname, regexName))
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
			if (!String.IsNullOrEmpty(brokers.mail))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(brokers.mail, regexMail))
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
			if (!String.IsNullOrEmpty(brokers.phoneNumber))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(brokers.phoneNumber, regexPhone))
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

			//si il n'y a pas d'erreur
			if (ModelState.IsValid)
            {
                db.brokers.Add(brokers);
                db.SaveChanges();
                return RedirectToAction("Succes");
            }
			else
			{
				return View(brokers);
			}
        }

		// GET: brokers/Edit/5
		public ActionResult Edit(int? id)
        {
            if (id == null)
            {
				return View("Error");
			}
            brokers brokers = db.brokers.Find(id);
            if (brokers == null)
            {
				return View("PageNotFound");
			}
            return View(brokers);
        }

        // POST: brokers/Edit/5
        // Afin de déjouer les attaques par sur-validation, activez les propriétés spécifiques que vous voulez lier. Pour 
        // plus de détails, voir  https://go.microsoft.com/fwlink/?LinkId=317598.
        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult Edit([Bind(Include = "idBroker,lastname,firstname,mail,phoneNumber")] brokers brokers)
        {
			//Vérification que le champ lastname n'est pas null ou vide
			if (!String.IsNullOrEmpty(brokers.lastname)) //si le champ lastname n'est pas vide ou null on vérifie la validité de l'entrée
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(brokers.lastname, regexName)) //si l'entrée utilisateur ne passe pas la regex ajout d'un message d'erreur
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
			if (!String.IsNullOrEmpty(brokers.firstname))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(brokers.firstname, regexName))
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
			if (!String.IsNullOrEmpty(brokers.mail))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(brokers.mail, regexMail))
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
			if (!String.IsNullOrEmpty(brokers.phoneNumber))
			{
				//Vérification de la validité de l'entrée
				if (!Regex.IsMatch(brokers.phoneNumber, regexPhone))
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

			if (ModelState.IsValid)
            {
                db.Entry(brokers).State = EntityState.Modified;
                db.SaveChanges();
                return RedirectToAction("Succes");
            }
			else
			{
				return View(brokers);
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