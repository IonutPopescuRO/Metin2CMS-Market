# Metin2 CMS - Market 

[![N|Solid](http://i.imgur.com/dS8151Q.png)](https://metin2cms.cf/)

This is an online market. Players can sell their items online game, and can be purchased by other players. The design is conceived bootstrap, is available on other devices than PC.

Communication with the database is done using the PDO extension. The platform is available in 8 languages:

  - English
  - Română
  - Français
  - Italiano
  - Português
  - Türk
  - Deutsch
  - Español

Data related platform are stored in a SQLite database, no longer need for a new database. It included a store of objects (Item-Shop). It can add items with bonuses and can make payments with paypal for dragon coin.

Attention! Order to that the website to work properly, last_play table must be updated. This script is provided along with the market.

     /*********
    * file:		    	input_login.cpp
    * description: 		Time update in real-time table last_play
    * date: 		    Tuesday, Aug 01 st 2016, 02:44am
    * author:           VegaS
    */
    
    //1.) Search:
    void CInputLogin::Entergame(LPDESC d, const char * data)
    {
    	LPCHARACTER ch;
    	[........................................]
    } 
    
    //2.) Add bellow:
    #ifdef ENABLE_UPDATE_LASTPLAY_REAL_TIME
    /*********
    * I put this verification level as not to over apply database updates elsewhere for characters that start at
    * first on the game that is at level 1, I believe that it is not necessary for a player of that level to have time adapted real-time.
    */
    	int pLevel = 0; // To begin initialize of level 5 ++
    
    	if (ch->GetLevel() > pLevel) 
    	{	
    		char pUpdateTime[1024];
    		snprintf(pUpdateTime, sizeof(pUpdateTime), "UPDATE player.player SET last_play = NOW() WHERE id = %u", ch->GetPlayerID());
    		std::auto_ptr<SQLMsg> sUpdate(DBManager::instance().DirectQuery(pUpdateTime));
    	}
    #endif
