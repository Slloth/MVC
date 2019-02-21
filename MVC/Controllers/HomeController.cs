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
    public class HomeController : Controller
    {
        private agendaEntities2 db = new agendaEntities2();

		// GET: Home
		public ActionResult Index()
		{
			var appointments = db.appointments.Include(a => a.brokers).Include(a => a.customers);
            return View(appointments.ToList());
        }

		//Vue de l'ôpération réussi
		public ActionResult Succes()
		{
			return View("Succes");
		}

        // GET: Home/Create
        public ActionResult Create(int? id)
        {
			if(id == null)
			{
				ViewBag.idBroker = new SelectList(db.brokers, "idBroker", "lastname");
				ViewBag.idCustomer = new SelectList(db.customers, "idCustomer", "lastname");
				return View();
			}
			else
			{
				ViewBag.idBroker = new SelectList(db.brokers, "idBroker", "lastname", id);
				ViewBag.idCustomer = new SelectList(db.customers, "idCustomer", "lastname");
				return View();
			}
        }

        // POST: Home/Create
        // Afin de déjouer les attaques par sur-validation, activez les propriétés spécifiques que vous voulez lier.
        // Pour plus de détails, voir  https://go.microsoft.com/fwlink/?LinkId=317598.
        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult Create([Bind(Include = "idAppointment,dateHour,idBroker,idCustomer")] appointments appointments)
        {
			//var date = Request.Form["startDatepicker"];//Récupération de la date du rdv dans la variable du même nom sous forme de string 
			//var hour = Request.Form["startTimepicker"];//Récupération de l'heure du rdv dans la variable Hour sous forme de string
			//var concat = date + " " + hour;//concaténation des 2 variables de sorte à correspondre au format DateTime
			//appointments.dateHour = Convert.ToDateTime(concat);//attribution de la valeur convertie au format datetime à l'attribut dateHour de l'objet appointmentToAdd
			//if (string.IsNullOrEmpty(date) || string.IsNullOrEmpty(hour))
   //         {
   //             ModelState.AddModelError("dateHour", "heure ou date manquante");
   //         }
			var brokerAlreadyUsed = db.appointments.Where(x => x.idBroker == appointments.idBroker && x.dateHour == appointments.dateHour).SingleOrDefault();
			if (brokerAlreadyUsed != null)
			{
				ModelState.AddModelError("dateHour", "Ce courtier possède déjà un rendez-vous à cette date");
			}
			var customerAlreadyUsed = db.appointments.Where(x => x.idCustomer == appointments.idCustomer && x.dateHour == appointments.dateHour).SingleOrDefault();
			if (customerAlreadyUsed != null)
			{
				ModelState.AddModelError("dateHour", "Ce client possède déjà un rendez-vous à cette date");
			}
			if (ModelState.IsValid)
            {
                db.appointments.Add(appointments);
                db.SaveChanges();
                return RedirectToAction("Index");
            }

            ViewBag.idBroker = new SelectList(db.brokers, "idBroker", "lastname", appointments.idBroker);
            ViewBag.idCustomer = new SelectList(db.customers, "idCustomer", "lastname", appointments.idCustomer);
            return View(appointments);
        }

        // GET: Home/Edit/5
        public ActionResult Edit(int? id)
        {
            if (id == null)
            {
				return View("Error");
            }
            appointments appointments = db.appointments.Find(id);
            if (appointments == null)
            {
				return View("PageNotFound");
            }
            ViewBag.idBroker = new SelectList(db.brokers, "idBroker", "lastname", appointments.idBroker);
            ViewBag.idCustomer = new SelectList(db.customers, "idCustomer", "lastname", appointments.idCustomer);
			return View(appointments);
        }

        // POST: Home/Edit/5
        // Afin de déjouer les attaques par sur-validation, activez les propriétés spécifiques que vous voulez lier. Pour 
        // plus de détails, voir  https://go.microsoft.com/fwlink/?LinkId=317598.
        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult Edit([Bind(Include = "idAppointment,dateHour,idBroker,idCustomer")] appointments appointments)
        {
			ViewBag.idBroker = new SelectList(db.brokers, "idBroker", "lastname", appointments.idBroker);
			ViewBag.idCustomer = new SelectList(db.customers, "idCustomer", "lastname", appointments.idCustomer);
			var isAlreadyUsed = db.appointments.Where(x => x.idBroker == appointments.idBroker && x.dateHour == appointments.dateHour || x.idCustomer == appointments.idCustomer && x.dateHour == appointments.dateHour).SingleOrDefault();
			if (isAlreadyUsed != null)
			{
				ModelState.AddModelError("dateHour", "cette plage Horraire possède déjà un Rendez-vous");
			}
			if (ModelState.IsValid)
            {
                db.Entry(appointments).State = EntityState.Modified;
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            return View(appointments);
        }

        // GET: Home/Delete/5
        public ActionResult Delete(int? id)
        {
            appointments appointments = db.appointments.Find(id);
            if (appointments == null)
            {
                return View("PageNotFound");
            }
            return View(appointments);
        }

        // POST: Home/Delete/5
        [HttpPost, ActionName("Delete")]
        [ValidateAntiForgeryToken]
        public ActionResult DeleteConfirmed(int id)
        {
            appointments appointments = db.appointments.Find(id);
            db.appointments.Remove(appointments);
            db.SaveChanges();
            return RedirectToAction("Index");
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