<div class="wrap apg" id="apg-support">

	<h1><?php _e( "How To Use", 'apg' ) ?></h1>

	<div id="HowToUse">

		<h2>ArCa Payment Gateway Free – թեստային տարբերակ</h2>
		<ol>
			<li>Աշխատում է միայն թեստային միջավայրում</li>
			<li>Նախատեսված է փարձնական գործարքներ իրականացնոլու և ինտեգրելու համար</li>
			<li>Աշխատում է միայն բանկի կողմից տրամադրված թեստային տվյալներով</li>
		</ol>


		<h2>ArCa Payment Gateway PRO տարբերակի ակտիվացում</h2>
		<ol>
			<li>Ակտիվացնելու համար անհրաժեշտ է մուտք գործել ձեր անձնական էջ <a href="https://store.planetstudio.am">store.planetstudio.am</a> կայքում</li>
			<li>Պատճենել ակտիվացման բանալին՝ My Account > My Certificates > Activation key</li>
			<li>Մուտք գործել ձեր Wordpress կայքի ադմին պանել, որպես ադմինիստրատոր</li>
			<li>Տեղադրել պատճենված ակտիվացման բանալին հավելվածի բաժնում՝ ակտիվացման բանալի դաշտում ArCa Gateway > <a href="/wp-admin/admin.php?page=dashboard" target="_blank">Dashboard</a> > Activation key</li>
			<li>Սեղմել Ակտիվացնել կոճակը Activate</li>
		</ol>


		<h2>Ինչ է պետք vPOS ստանալու համար</h2>
		<ol>
			<li>Հարկավոր է դիմել բանկին vPOS հաշիվ կամ մի քանի արժույթով հաշիվներ տրամադրելու համար
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Բանկի պահանջները՝

						<ul style="list-style-type:square; margin-left:20px;">
							<li>Կայքը պարտադիր պետք է ունենա հայերեն տարբերակ</li>
							<li>Դիմողը պետք է լինի իրավաբանական անձ (Կազմակերպություն)</li>
							<li>Կազմակերպությունը պետք է լինի ՀՀ ռեզիդենտ</li>
							<li>Կայքայում պետք է առկա լինի հետևյալ տեղեկատվությունը
								<ul style="list-style-type:circle; margin-left:20px;">
									<li>Կազմակերպության անվանում</li>
									<li>Գործունեության հասցե</li>
									<li>Հեռախոս</li>
									<li>ՀՎՀՀ</li>
									<li>Ծառայության մատուցման պայմանները (Terms and conditions)</li>
									<li>Չեղարկման քաղաքականություն (Cancellation policy)</li>
									<li>Գաղտնիության քաղաքականություն (գաղտնի տվյալների մշակման և պահպանման վերաբերյալ ծանուցում)</li>		
								</ul>
							</li>
						</ul>

					</li>
				</ul>
			</li>	
		</ol>


		<h2>ԱռՔա համակարգի անդամ հանդիսացող բանկերի ինտեգրում - IPay</h2>
		<ol>
			<li>Դիմումը հաստատվելուց հետո բանկը կտրամադրի IPay թեստային համակարգի մուտքային տվյալները որոնց միջոցով կատարում եք կարգավորումները և մի քանի թեստային գործարք
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>API Login, API Password և թեստային քարտի տվյալները
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Ուշադրություն` պարտադիր է փոխել բոլոր արժույթով հաշիվների *_admin և *_api օգտատերի գաղտնաբառերը հետևյալ <a href="https://ipay.arca.am/payment/admin/">հասցեով</a>
								<ul style="list-style-type:circle; margin-left:20px;">
									<li>Առանձ փոխելու այն կիրառելու դեպքում ձեր IPay հաշիվը կարգելափակվի ԱռՔա համակարգի կողմից</li>
									<li>Եթե ձեր հաշիվը արգելափակվել է անհրաժոշտ է դիմել ձեր բանկին այն վերագործարկելու համար</li>
									<li>Եթե <a href="/wp-admin/admin.php?page=errorlogs" target="_blank"><?php _e( "Error logs", 'apg' ) ?></a> բաժնում ստանում եք "Error code 5" ապա ձեր հաշիվը արգելափակվել է</li>
								</ul>
							</li>
						</ul>
					</li>
					<li>Այնուհետև բանկը տրամադրում է իրական միջավայրի տվյալները</li>
					<li>Որոշ բանկեր նախապես տրամադրում են իրական միջավայրի տվյալները կամ երկուսը միասին</li>
				</ul>
			</li>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=config" target="_blank"><?php _e( "vPOS Configuration", 'apg' ) ?></a> բաժնում ընտրեք համապատասխան <?php _e( "Working mode", 'apg' ) ?>
				<ul style="list-style-type:disc; margin-left:20px;">
					<li><?php _e( "Test server", 'apg' ) ?>
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Թեստային միջավայրում չեք կարող օգտագործել իրական միջավայրի տվյալները</li>
							<li>Թեստային միջավայրում գործարքները կատարվում են բացառապես բանկի կողմից տրամադրված թեստային քարտով</li>
						</ul>
					</li>
					<li><?php _e( "Real server", 'apg' ) ?>
						<ul style="list-style-type:square; margin-left:20px;">
							<li>ArCa Payment Gateway – Test տարբերակը չի աշխատում իրական միջավայրում</li>
						</ul>
					</li>
				</ul>
			</li>	
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=currency" target="_blank"><?php _e( "Currency", 'apg' ) ?></a> բաժնում ակտիվացրեք անհրաժեշտ արժույթները</li>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=config" target="_blank"><?php _e( "vPOS Configuration", 'apg' ) ?></a> բաժնում
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Տեղադրեք <?php _e( "API username:", 'apg' ) ?> և <?php _e( "API password:", 'apg' ) ?> տվյալները բոլոր արժույթների հաշիվների համար (արդեն փոխված գաղտնաբառը)</li>
					<li>Ընտրեք լրելյալ լեզուն
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Դա բանկի վճարման էջի լեզուն է</li>
							<li>Arca Payment Gateway-ի shotcode-ը օգտագործելիս կարող եք նշել լեզուն, եթե չեք նշում համակարգը ընտրում է լրելյալ լեզուն [arca-pg-form productid="1" language="hy"]</li>
							<li>WooCommerce կամ GiveWP օգտագործելիս համակարգը ընտրում է կայքի լեզուն</li>
						</ul>
					</li>
					<li>Ընտրեք լրելյալ արժույթը
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Arca Payment Gateway-ի shotcode օգտագործելիս կարող եք նշել արժույթը, եթե չեք նշում համակարգը ընտրում է լրելյալ արժույթը [arca-pg-form productid="1" currency="AMD"]</li>
							<li>WooCommerce կամ GiveWP օգտագործելիս համակարգը ընտրում է WooCommerce կամ GiveWP-ով նշված արժույթը</li>
						</ul>
					</li>
					<li>Ընտրեք <?php _e( "Orders number prefix:", 'apg' ) ?>
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Վճարման համարները կսկսվեն այդպես (Օրինակ՝ AF-1002)</li>
						</ul>
					</li>						
					<li>Ընտրեք <?php _e( "WooCommerce order status:", 'apg' ) ?>
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Վճարումից հետո WooCommerce պատվերը կստանա ընտրված կարգավիճակը</li>
						</ul>
					</li>
				</ul>					
			</li>			
		</ol>

		<h2>Ameriabank-ի ինտեգրում</h2>
		<ol>
			<li>Դիմումը հաստատվելուց հետո բանկը կտրամադրի թեստային համակարգի մուտքային տվյալները որոնց միջոցով կատարում եք կարգավորումները և մի քանի թեստային գործարք
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>ClientID, Username, Password, OrderID միջակայք և թեստային քարտի տվյալներ</li>
					<li>Որից հետո բանկը տրամադրում է իրական միջավայրի տվյալները</li>
				</ul>
			</li>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=config" target="_blank"><?php _e( "vPOS Configuration", 'apg' ) ?></a> բաժնում ընտրեք համապատասխան <?php _e( "Working mode", 'apg' ) ?>
				<ul style="list-style-type:disc; margin-left:20px;">
					<li><?php _e( "Test server", 'apg' ) ?>
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Թեստային միջավայրում չեք կարող օգտագործել իրական միջավայրի տվյալները</li>
							<li>Թեստային միջավայրում գործարքները կատարվում են բացառապես բանկի կողմից տրամադրված թեստային քարտով</li>
						</ul>
					</li>
					<li><?php _e( "Real server", 'apg' ) ?>
						<ul style="list-style-type:square; margin-left:20px;">
							<li>ArCa Payment Gateway – Test տարբերակը չի աշխատում իրական միջավայրում</li>
						</ul>
					</li>
				</ul>
			</li>	
			<li><?php _e( "Currency", 'apg' ) ?> բաժնում ակտիվացրեք անհրաժեշտ արժույթները</li>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=config" target="_blank"><?php _e( "vPOS Configuration", 'apg' ) ?></a> բաժնում
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Տեղադրեք <?php _e( "Client ID:", 'apg' ) ?> -ին</li>
					<li>Տեղադրեք <?php _e( "API username:", 'apg' ) ?> և <?php _e( "API password:", 'apg' ) ?> տվյալները բոլոր արժույթների հաշիվների համար</li>
					<li>Ուշադրություն` թեստային միջավարի համար գումարը պարտադիր պետք է լինի 10</li>
					<li>Թեստային միջավարի համար տեղադրեք <?php _e( "Start of the OrderID range in the test mode:", 'apg' ) ?> և <?php _e( "End of the OrderID range in the test mode:", 'apg' ) ?></li>
					<li>Ընտրեք լրելյալ լեզուն
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Դա բանկի վճարման էջի լեզուն է</li>
							<li>Arca Payment Gateway-ի shotcode օգտագործելիս կարող եք նշել լեզուն, եթե չեք նշում համակարգը ընտրում է լրելյալ լեզուն [arca-pg-form productid="1" language="hy"]</li>
							<li>WooCommerce կամ GiveWP օգտագործելիս համակարգը ընտրում է կայքի լեզուն</li>
						</ul>
					</li>
					<li>Ընտրեք լրելյալ արժույթը
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Arca Payment Gateway-ի shotcode օգտագործելիս կարող եք նշել արժույթը, եթե չեք նշում համակարգը ընտրում է լրելյալ արժությը [arca-pg-form productid="1" currency="AMD"]</li>
							<li>WooCommerce կամ GiveWP օգտագործելիս համակարգը ընտրում է WooCommerce կամ GiveWP-ով նշված արժույթը</li>
						</ul>
					</li>
					<li>Ընտրեք <?php _e( "Orders number prefix:", 'apg' ) ?>
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Վճարման համարները կսկսվեն այդպես (Օրինակ՝ AF-1002)</li>
						</ul>
					</li>						
					<li>Ընտրեք <?php _e( "WooCommerce order status:", 'apg' ) ?>
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Վճարումից հետո WooCommerce պատվերը կստանա ընտրված կարգավիճակը</li>
						</ul>
					</li>
				</ul>					
			</li>			
		</ol>	



		<h2>InecoBank-ի ինտեգրում</h2>
		<ol>
			<li>Դիմումը հաստատվելուց հետո բանկը կտրամադրի մուտքային Username և Password տվյալները</li>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=config" target="_blank"><?php _e( "vPOS Configuration", 'apg' ) ?></a> բաժնում ընտրեք <?php _e( "Real server", 'apg' ) ?> տարբերակը
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Ուշադրություն` թեստային միջավարի համար գումարը պարտադիր պետք է լինի 10</li>
				</ul>
			</li>	
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=currency" target="_blank"><?php _e( "Currency", 'apg' ) ?></a> բաժնում ակտիվացրեք անհրաժեշտ արժույթները</li>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=config" target="_blank"><?php _e( "vPOS Configuration", 'apg' ) ?></a> բաժնում
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Տեղադրեք <?php _e( "API username:", 'apg' ) ?> և <?php _e( "API password:", 'apg' ) ?> տվյալները բոլոր արժույթների հաշիվների համար</li>
					<li>Ընտրեք լրելյալ լեզուն
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Դա բանկի վճարման էջի լեզուն է</li>
							<li>Arca Payment Gateway-ի shotcode օգտագործելիս կարող եք նշել լեզուն, եթե չեք նշում համակարգը ընտրում է լրելյալ լեզուն [arca-pg-form productid="1" language="hy"]</li>
							<li>WooCommerce կամ GiveWP օգտագործելիս համակարգը ընտրում է կայքի լեզուն</li>
						</ul>
					</li>
					<li>Ընտրեք լրելյալ արժույթը
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Arca Payment Gateway-ի shotcode օգտագործելիս կարող եք նշել արժույթը, եթե չեք նշում համակարգը ընտրում է լրելյալ արժույթը [arca-pg-form productid="1" currency="AMD"]</li>
							<li>WooCommerce կամ GiveWP օգտագործելիս համակարգը ընտրում է WooCommerce կամ GiveWP-ով նշված արժույթը</li>
						</ul>
					</li>
					<li>Ընտրեք <?php _e( "Orders number prefix:", 'apg' ) ?>
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Վճարման համարները կսկսվեն այդպես (Օրինակ՝ AF-1002)</li>
						</ul>
					</li>						
					<li>Ընտրեք <?php _e( "WooCommerce order status:", 'apg' ) ?>
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Վճարումից հետո WooCommerce պատվերը կստանա ընտրված կարգավիճակը</li>
						</ul>
					</li>
				</ul>					
			</li>			
		</ol>	


		<h2>Idram համակարգի ինտեգրում</h2>
		<ol>
			<li>Դիմումը հաստատվելուց հետո Idram-ին անհրաժեշտ է փոխանցել "Callback URL"-ները
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Այն կարող եք վերձնել ArCa Gateway > <a href="/wp-admin/admin.php?page=config" target="_blank"><?php _e( "Idram Configuration", 'apg' ) ?></a> > Idram Callback URLs բաժնում</li>
				</ul>			
			</li>
			<li>Որից հետո Idram-ը կտրամադրի մուտքային տվյալները ID և Secret key
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Ցանկության դեպքում Idram-ը տրամադրում է նաև թեստային միջավայրի տվյալներ</li>
				</ul>				
			</li>
			<li>Տեղադրեք դրանք ArCa Gateway > <a href="/wp-admin/admin.php?page=config" target="_blank"><?php _e( "Idram Configuration", 'apg' ) ?></a> բաժնում
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Եթե Idram-ը տրամադրել է "Roket Line" ակտիվացրեք այն</li>
				</ul>			
			</li>
			<li>Ընտրեք լրելյալ լեզուն
				<ul style="list-style-type:square; margin-left:20px;">
					<li>Դա Idram վճարման էջի լեզուն է</li>
					<li>Arca Payment Gateway-ի shotcode-ը օգտագործելիս կարող եք նշել լեզուն, եթե չեք նշում համակարգը ընտրում է լրելյալ լեզուն [arca-pg-form productid="1" language="hy"]</li>
					<li>WooCommerce կամ GiveWP օգտագործելիս համակարգը ընտրում է կայքի լեզուն</li>
				</ul>
			</li>
			<li>Ընտրեք <?php _e( "WooCommerce order status:", 'apg' ) ?>
				<ul style="list-style-type:square; margin-left:20px;">
					<li>Վճարումից հետո WooCommerce պատվերը կստանա ընտրված կարգավիճակը</li>
				</ul>
			</li>
			<li>Ուշադրություն` Idram համակարգով գործարքները կատարվելու են բացառապես դրամով, անկախ նրանից թե ինչ արժույթ է ընտրված Woocommerce, GiveWP կամ ArCa Payment Gateway-ի վճարման էջում</li>
		</ol>	

		<h2>Woocommerce-ին ինտեգրում</h2>
		<ol>
			<li>ArCa Payment Gateway-ը Woocommerce-ին ինտեգրելու համար անհրաժեշտ է WooCommerce > Settings > <a href="/wp-admin/admin.php?page=wc-settings&tab=checkout" target="_blank">Payments</a> բաժնում
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>ակտիվացնել ArCa Payment Gateway</li>
					<li>ակտիվացնել Idram Payment Gateway</li>
				</ul>			
			</li>
			<li>WooCommerce > Settings > <a href="/wp-admin/admin.php?page=wc-settings&tab=general" target="_blank">General</a> > Currency options > Currency բաժնում ընտրել այն արժույթը որի համար բանկը տրամադրել է vPOS</li>
		</ol>	


		<h2>GiveWP Donation-ին ինտեգրում</h2>
		<ol>
			<li>ArCa Payment Gateway-ը GiveWP-ին ինտեգրելու համար անհրաժեշտ է Donations > Settings > Payment Gateways > <a href="/wp-admin/edit.php?post_type=give_forms&page=give-settings&tab=gateways" target="_blank">Gateways</a> բաժնում
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>ակտիվացնել ArCa Payment Gateway</li>
					<li>ակտիվացնել Idram Payment Gateway</li>
				</ul>			
			</li>
			<li>Donations > Settings > General > <a href="/wp-admin/edit.php?post_type=give_forms&page=give-settings&tab=general&section=currency-settings" target="_blank">Currency</a> բաժնում ընտրել այն արժույթը որի համար բանկը տրամադրել է vPOS</li>
		</ol>	


		<h2>ArCa Gateway վճարային էջի ստեղծում</h2>
		<ol>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=checkoutform" target="_blank">Checkout form</a> բաժնում կարգավորեք վճարային էջը
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>ArCa Gateway > Checkout form > <a href="/wp-admin/admin.php?page=checkoutform" target="_blank">Billing details</a> բաժնում ընտրեք անհրաժեշտ դաշտերը</li>
					<li>Ընտրեք, որ դաշտենը պետք է լինեն պարտադիր լրացման համար</li>
					<li>ArCa Gateway > Checkout form > <a href="/wp-admin/admin.php?page=checkoutform" target="_blank">Email from</a> դաշտում մուտքագրեք էլ. փոստի հասցեն
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Տվյալ էլ. փոստից ուղարկվելու են գործարքների մասին ծանուցումները</li>
							<li>Եթե էլ. փոստը լրացված չէ ծանուցումները կուղարկվեն կայքի ադմինիստրատորի էլ. փոստից</li>
						</ul>					
					</li>
					<li>ArCa Gateway > Checkout form > <a href="/wp-admin/admin.php?page=checkoutform" target="_blank">Checkuot page</a> բաժնում նշված է վճարային էջը, որտեղ տեղադրված է [arca-pg-form] Shortcode-ը
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Ցանկության դեպքում կարող եք այն խմբագրել և ձևավորել</li>
							<li>Որպես «Վճարային էջ» կարող եք ընտրել ցանկացած այլ էջ</li>
						</ul>
					</li>
					<li>ArCa Gateway > Checkout form > <a href="/wp-admin/admin.php?page=checkoutform" target="_blank">Privacy Policy Page</a> բաժնում նշված է «Կանոններ և պայմաններ» էջը
						<ul style="list-style-type:square; margin-left:20px;">
							<li>Խմբագրեք այն և տեղադրեք ձեր «Կանոններ և պայմաններ»-ը</li>
							<li>Որպես «Կանոններ և պայմաններ» էջ կարող եք ընտրել ցանկացած այլ էջ</li>
						</ul>
					</li>
				</ul>
			</li>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=pricelist" target="_blank">Price list</a> բաժնում ստեղծեք գնացուցակ
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Լրացրեք ապրանքի, ծառայության կամ այլ վճարման նպատակի անվանումը</li>
					<li>Նկարագրությունը</li>
					<li>Գինը</li>
				</ul>			
			</li>
			<li>Ստեղծված գնացուցակից ընտրեք անհրաժեշտ տողը և սեղմեք Shortcode կոճակը</li>
			<li>Բացված պատուհանում ընտրեք անհրաժեշտ տարբերակները և պատճենեք Shortcode-ից ձեր նախընտրած տարբերակը
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Արժույթը պետք է համապատասխանի բանկի կողմից տրամադրված vPOS-ի արժույթին</li>
				</ul>		
			</li>
			<li>Տեղադրեք այն ցանկացաց էջում</li>
		</ol>	


		<h2>Վճարային էջի ստեղծում սեփական HTML-ով կամ Form Builder-ներով</h2>
		<ol>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=pricelist" target="_blank">Price list</a> բաժնում ստեղծեք գնացուցակ
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Լրացրեք ապրանքի, ծառայության կամ այլ վճարման նպատակի անվանումը</li>
					<li>Նկարագրությունը</li>
					<li>Գինը</li>
				</ul>			
			</li>
			<li>Անհրաժեշտ է կատարել POST կամ GET հարցում հետևյալ պարտադիր պարամետրերով
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>language = "hy" (hy, en կամ ru)</li>
					<li>currency = 051 (AMD:051, RUB:643, USD:840, EUR:978)</li>
					<li>description = "text ..." (Payment description)</li>
					<li>productId = "1" (Product ID from price list)</li>
				</ul>		
			</li>
			<li>Օրինակ՝
				<pre>

&#60;form method="post"&#62;

	&#60;!-- 
	.
	Your additional form elements
	.
	--&#62;

	&#60;!-- payment method switcher --&#62;
	&#60;label for="credit_card">Credit Card&#60;/label&#62;
	&#60;input type="radio" name="arca_process" value="register" checked&#62;
	&#60;label for="idram">Idram&#60;/label&#62;
	&#60;input type="radio" name="arca_process" value="idram" id="idram"&#62;	

	&#60;!-- payment form language --&#62;
	&#60;input type="hidden" name="language" value="hy"&#62;

	&#60;!-- payment form language --&#62;
	&#60;input type="hidden" name="currency" value="051"&#62;

	&#60;!-- payment description --&#62;
	&#60;input type="hidden" name="description" value="test"&#62;

	&#60;!-- product ID --&#62;
	&#60;input type="hidden" name="productId" value="1"&#62;

	&#60;input type="submit" value="Pay"&#62;
&#60;/form&#62;

Կամ մեկ հղումով բանկային քարտի միջոցով

&#x3C;a href=&#x22;?arca_process=register&#x26;language=hy&#x26;currency=051&#x26;productId=1&#x26;description=text&#x26;additional1=anyvalue1&#x26;additional2=anyvalue2&#x22;&#x3E;One-click card payment&#x3C;/a&#x3E;

Կամ մեկ հղումով Idram-ի միջոցով

&#x3C;a href=&#x22;?arca_process=idram&#x26;language=hy&#x26;productId=1&#x26;description=text&#x26;additional1=anyvalue1&#x26;additional2=anyvalue2&#x22;&#x3E;One-click Idram payment&#x3C;/a&#x3E;
				</pre>
			</li>
		</ol>


		<h2>ArCa Gateway Հատուկ գումարով վճարման ձևի ստեղծում</h2>
		<ol>
			<li>Հատում գումարով վճարում կատարելու համար անհրաժեշտ է տեղադրել հետևյալ Shortcode-ը
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Բանկային քարտով վճարման համար [arca-pg-button-custom-amount language="hy" description="Custom amount payment with card" amount="10"]</li>
					<li>Idram-ով վճարման համար [arca-pg-button-custom-amount language="hy" description="Custom amount payment with card" amount="10" arca_process="idram"]</li>
				</ul>			
			</li>
		</ol>	


		<h2>ArCa Gateway գնացուցակի և նկարագրության թարգմանություն</h2>
		<ol>
			<li>Եթե կայքի թարգմանության համար օգտագործում եք Polylang կամ WPML տեղադրեք <a href="https://wordpress.org/plugins/easy-translate/" target="_blank">WPC Simple Translate</a> փլագինը</li>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=pricelist" target="_blank">Price list</a> բաժնում տեղադրեք թարգմանությունները հետևյալ ֆորմատով
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>[:en]Hello World[:fr]Bonjour le monde[:de]Hallo Welt[:]</li>
					<li>Ավելի մանրամասն տեղոկուտյուն կարող եք ստանալ WPC Simple Translate փլագինի էջում</li>
					<li>WPC Simple Translate փլագինի միջոցով կարող եք նաև թարգմանել կայքի ցանկացած տեքստ ցանկացած մասում</li>
				</ul>			
			</li>
		</ol>	

		
		<h2>Orders և Error logs բաժիններ</h2>
		<ol>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=оrderlog" target="_blank">Orders</a> բաժնում կարող եք տեսնել քարտային և Idram բոլոր գործարքների մանրամասները
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Order number: Գործարքի հերթական համար</li>
					<li>Order id: Վճարային համակարգի կողմից ստացված գործարքի համար</li>
					<li>Product id: ArCa Gateway > Price list > ProductID</li>
					<li>WC order: WooCommerce > ProductID</li>
					<li>Amount: Գործարքի գումար</li>
					<li>Currency: Գործարքի արժույթ</li>
					<li>Error code: Սխալի համար` 0 կամ դատարկ դաշտը նշանակում է սխալ չկա</li>
					<li>Payment state: Վճարման կարգավիճակ</li>
					<li>Order date: Գործարքի ամսաթիվ</li>
					<li>Email sent: Հաճախորդին էլ․ փոստի ծանուցման կարգավիճակ</li>
					<li>Order status: Գործարքի մանրամասն լոգերը ստացված վճարման համակարգի կողմից ըստ քայլերի</li>
					<li>Order details: Գործարքի մասին հավաքված տվյալներ</li>
				</ul>			
			</li>
			<li>ArCa Gateway > <a href="/wp-admin/admin.php?page=errorlogs" target="_blank">Error logs</a> բաժնում կարող եք տեսնել սխալների լոգերը
				<ul style="list-style-type:disc; margin-left:20px;">
					<li>Date: Սխալի գրանցման ամսաթիվը</li>
					<li>Error: Սխալի նկարագրությունը, համարը</li>
					<li>Սխալների համարների բացատրությունը կարող եք տեսնել համապատասխան վճարային համագարգի տեխնիկական նկարագրության մեջ
						<ul style="list-style-type:square; margin-left:20px;">
							<li><a href="https://cabinet.arca.am/file_manager/Merchant%20Manual%20RU.pdf" target="_blank">ԱռՔա վճարային համակարգ - IPay</a></li>
							<li><a href="https://servicestest.ameriabank.am/VPOS/help" target="_blank">Ameriabank</a></li>
							<li>Inecobank վճարային համակարգ</li>
							<li>Idram վճարային համակարգ</li>
						</ul>					
					</li>
					<li>Եթե բանկի վճարման էջ տեղափոխվելիս ստանում եք "404 Not Found" սխալ դա նշանակում է, որ վճարային համակարգը չի տեղադրել ձեր վճարման էջը, տվյալ լեզվի համար կամ ընդհանրապես, խնդիրը շտկելու համար անհրաժեշտ է կապ հաստատել ձեր բանկի հետ</li>
				</ul>			
			</li>
		</ol>
		
	</div>	

</div>
